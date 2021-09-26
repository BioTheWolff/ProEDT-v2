{ pkgs }: {
    deps = [
    pkgs.zig
        pkgs.php74
        pkgs.postgresql
        pkgs.php74Packages.composer
        pkgs.sass
    ];
}