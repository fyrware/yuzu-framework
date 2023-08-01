globalThis.yz ??= {};

yz.icons = {
    thin: undefined,
    light: undefined,
    regular: undefined,
    bold: undefined,
    duotone: undefined,
    solid: undefined
};

yz.getIconSet = function yzGetIconSet(appearance) {
    return yz.icons[appearance];
}

yz.setIconSet = function yzSetIconSet(appearance, iconSet) {
    yz.icons[appearance] = iconSet;
}