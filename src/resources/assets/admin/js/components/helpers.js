Number.prototype.countDecimals = function() {

    if (Math.floor(this.valueOf()) === this.valueOf()) return 0;

    let str = Math.abs(this).toString();

    if (str.indexOf(".") !== -1 && str.indexOf("-") !== -1) {
        return str.split("-")[1] || 0;
    } else if (str.indexOf(".") !== -1) {
        return str.split(".")[1].length || 0;
    }
    return str.split("-")[1] || 0;
};
