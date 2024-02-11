class YzIconService {

    #thin;
    #light;
    #regular;
    #bold;
    #duotone;
    #solid;

    list(appearance) {
        switch (appearance) {
            case 'thin':
                return this.#thin;
            case 'light':
                return this.#light;
            case 'regular':
                return this.#regular;
            case 'bold':
                return this.#bold;
            case 'duotone':
                return this.#duotone;
            case 'solid':
                return this.#solid;
        }
    }

    get(appearance, glyph) {
        switch (appearance) {
            case 'thin':
                return this.#thin[glyph];
            case 'light':
                return this.#light[glyph];
            case 'regular':
                return this.#regular[glyph];
            case 'bold':
                return this.#bold[glyph];
            case 'duotone':
                return this.#duotone[glyph];
            case 'solid':
                return this.#solid[glyph];
        }
    }

    set(appearance, iconSet) {
        switch (appearance) {
            case 'thin':
                this.#thin = iconSet;
                break;
            case 'light':
                this.#light = iconSet;
                break;
            case 'regular':
                this.#regular = iconSet;
                break;
            case 'bold':
                this.#bold = iconSet;
                break;
            case 'duotone':
                this.#duotone = iconSet;
                break;
            case 'solid':
                this.#solid = iconSet;
                break;
        }
    }
}