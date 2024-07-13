<?php

class Yz_Calendar {

    public static function render(array $props): void {
        global $yz;

        $id     = $yz->tools->get_value($props, 'id');
        $month  = $yz->tools->get_value($props, 'month', date('n'));
        $year   = $yz->tools->get_value($props, 'year', date('Y'));
        $events = $yz->tools->get_value($props, 'events', []);
        $today  = $yz->tools->get_value($props, 'today', 0);

        foreach ($events as &$event) {

            for ($i = 0; $i < count($event['dates']); $i++) {
                $date = $event['dates'][$i];

                if (is_string($date)) {
                    $event['dates'][$i] = [
                        'year'  => (int)date('Y', strtotime($date)),
                        'month' => (int)date('n', strtotime($date)),
                        'day'   => (int)date('j', strtotime($date))
                    ];
                }
            }
        }

        $yz->html->grid_layout([
            'as'      => 'ol',
            'id'      => $id,
            'class'   => 'calendar',
            'columns' => 7,
            'data'    => [
                'month'    => $month,
                'year'     => $year
            ],
            'children' => function() use($yz, $today, $month, $year, $events) {
                $current_year       = (int)date('Y', strtotime("$year-$month-1"));
                $current_month      = (int)date('n', strtotime("$year-$month-1"));
                $current_month_days = (int)date('t', strtotime("$year-$month-1"));
                $first_day_offset    = (int)date('w', strtotime("$year-$month-1"));

                for ($i = 0; $i < 7 * 5; $i++) {
                    $day_number = $i;
                    $month_number = $current_month;
                    $year_number = $current_year;

                    if ($i < $first_day_offset) {
                        $previous_month      = $current_month - 1;
                        $previous_month_year = (int)date('Y', strtotime("$current_year-$current_month-1 -1 month"));
                        $previous_month_days = (int)date('t', strtotime("$previous_month_year-$previous_month-1"));
                        $day_number          = $previous_month_days - $first_day_offset + $i + 1;
                        $month_number        = $previous_month;
                    } else if ($i > $current_month_days + $first_day_offset - 1) {
                        $day_number = $i - $current_month_days - $first_day_offset + 1;

                        if ($current_month === 12) {
                            $year_number = $current_year + 1;
                            $month_number = 1;
                        } else {
                            $month_number = $current_month + 1;
                        }
                    } else {
                        $day_number -= $first_day_offset - 1;
                    }

                    $day_events = $yz->tools->filter_array($events, function($event) use($year_number, $month_number, $day_number) {
                        foreach ($event['dates'] as $date) {
                            if ($date['year'] === $year_number && $date['month'] === $month_number && $date['day'] === $day_number) {
                                return true;
                            }
                        }
                        return false;
                    });

                    $day_events = $yz->tools->sort_array($day_events, function($a, $b) {
                        return count($b['dates']) <=> count($a['dates']);
                    });

                    $yz->html->element('li', [
                        'class' => 'yz calendar-day',
                        'data' => [
                            'day'   => $day_number,
                            'month' => $month_number,
                            'year'  => $current_year
                        ],
                        'children' => function() use($yz, $today, $current_year, $current_month, $day_number, $day_events) {
                            $yz->html->flex_layout([
                                'gap'       => 5,
                                'alignment' => 'stretch',
                                'direction' => 'column',
                                'children'  => function() use($yz, $today, $current_year, $current_month, $day_number, $day_events) {
                                    $yz->html->flex_layout([
                                        'gap' => 5,
                                        'alignment' => 'center',
                                        'children' => function() use($yz, $today, $current_year, $current_month, $day_number) {
                                            $today_year  = (int)date('Y');
                                            $today_month = (int)date('n');

                                            $yz->html->text($day_number, [ 'class' => 'calendar-day-number' ]);

                                            if ($day_number === $today && $current_month === $today_month && $current_year === $today_year) {
                                                $yz->html->text('&mdash; Today', [ 'class' => 'calendar-day-today-label' ]);
                                            }
                                        }
                                    ]);

                                    foreach ($day_events as $event) {
                                        $event_date = $yz->tools->find_value($event['dates'], function($date) use($day_number) {
                                            return $date['day'] === $day_number;
                                        });

                                        $day_classes = [
                                            'calendar-day-event'
                                        ];

                                        if (count($event['dates']) > 1) {
                                            $day_classes[] = 'calendar-day-event-multi';
                                        }

                                        if ($event_date === $yz->tools->first($event['dates'])) {
                                            $day_classes[] = 'calendar-day-event-first';
                                        }

                                        if ($event_date === $yz->tools->last($event['dates'])) {
                                            $day_classes[] = 'calendar-day-event-last';
                                        }

                                        $yz->html->flex_layout([
                                            'as'    => 'div',
                                            'gap'   => 5,
                                            'class' => $day_classes,
                                            'data'  => [
                                                'event_id' => $event['id'],
                                                'event_date' => $event_date['year'] . '-' . $event_date['month'] . '-' . $event_date['day'],
                                                'event'      => esc_attr(json_encode($event))
                                            ],
                                            'children' => function() use($yz, $event, $event_date) {
                                                $yz->html->text($event['title'], [ 'class' => 'calendar-day-event-name' ]);

                                                if (count($event['dates']) > 1) {
                                                    $yz->html->text($yz->tools->index_of($event['dates'], $event_date) + 1 . '/' . count($event['dates']), [ 'class' => 'calendar-day-event-count' ]);
                                                }
                                            }
                                        ]);
                                    }
                                }
                            ]);
                        }
                    ]);
                }
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="calendar">
            .yz.calendar {
                list-style: none;
                padding: 0;
                margin: 0;
                border-radius: 4px;
                border-top: 1px solid #ccc;
                border-left: 1px solid #ccc;
            }

            .yz.calendar-header {
                grid-column: 1 / span 7;
            }

            .yz.calendar-day {
                width: 100%;
                padding-bottom: 100%;
                margin-bottom: 0;
                border-bottom: 1px solid #ccc;
                border-right: 1px solid #ccc;
                position: relative;
            }

            .yz.calendar-day:hover::before {
                content: '';
                position: absolute;
                inset: 0;
                background: var(--yz-notification-color);
                opacity: 0.125;
            }

            .yz.calendar-day:nth-of-type(7n + 1),
            .yz.calendar-day:nth-of-type(7n -7) {
                background-color: rgb(0 0 0 / 2.5%);
            }

            .yz.calendar-day:nth-of-type(7) {
                border-radius: 0 4px 0 0;
            }

            .yz.calendar-day:nth-of-type(35) {
                border-radius: 0 0 4px 0;
            }

            .yz.calendar-day:nth-of-type(29) {
                border-radius: 0 0 0 4px;
            }

            .yz.calendar-day > .yz.flex-layout {
                width: 100%;
                box-sizing: border-box;
                padding: 10px;
                position: absolute;
            }

            .yz.calendar-day-number {
                font-size: 16px;
                font-weight: 700;
                color: #666;
                padding-left: 5px;
            }

            .yz.calendar-day-today-label {
                font-size: 12px;
                /*font-weight: 700;*/
                color: #666;
            }

            .yz.calendar-day-event {
                background: var(--yz-notification-color);
                color: white;
                width: 100%;
                height: 30px;
                padding: 6px 10px 4px;
                box-sizing: border-box;
                border-radius: 4px;
                font-size: 12px;
                cursor: pointer;
            }

            .yz.calendar-day-event-name {
                flex-grow: 1;
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
            }

            .yz.calendar-day-event-count {
                font-size: 10px;
                opacity: 0.66;
            }
        </style>
    <?php }

    public static function render_script(): void { ?>
        <script>
            yz.ready().observe(() => {
                // yz('.yz.calendar', container).forEach((calendar) => {
                //     const month    = calendar.dataset.month;
                //     const year     = calendar.dataset.year;
                //
                //     yz('.yz.calendar-day', calendar).forEach((day) => {
                //
                //         yz('.yz.calendar-day-event', day).forEach((event) => {
                //
                //         });
                //     });
                // });
            });
        </script>
    <?php }
}