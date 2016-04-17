$(function () {
    var from_time = parseInt($("#start").val(), 10);
    var now = parseInt($("#end").val(), 10);
    $("#slider-range").slider({
        range: true,
        min: from_time,
        max: now,
        step: 1800,
        values: [from_time, now],
        slide: function (event, ui) {
            $("#start").val(ui.values[0]);
            $("#end").val(ui.values[1]);
            $("#amount").val(formatDate(new Date(ui.values[0] * 1000), "MM月DD日hh時mm分") + " - " + formatDate(new Date(ui.values[1] * 1000), "MM月DD日hh時mm分"));
        },
        stop: function( event, ui ) {
            loadData();
        }
    });
    $("#amount").val((formatDate(new Date($("#slider-range").slider("values", 0) * 1000), "MM月DD日hh時mm分")) +
        " - " + (formatDate(new Date($("#slider-range").slider("values", 1) * 1000), "MM月DD日hh時mm分")));
});

/**
 * 日付をフォーマットする
 * @param  {Date}   date     日付
 * @param  {String} [format] フォーマット
 * @return {String}          フォーマット済み日付
 */
var formatDate = function (date, format) {
    if (!format) format = 'YYYY-MM-DD hh:mm:ss.SSS';
    format = format.replace(/YYYY/g, date.getFullYear());
    format = format.replace(/MM/g, ('0' + (date.getMonth() + 1)).slice(-2));
    format = format.replace(/DD/g, ('0' + date.getDate()).slice(-2));
    format = format.replace(/hh/g, ('0' + date.getHours()).slice(-2));
    format = format.replace(/mm/g, ('0' + date.getMinutes()).slice(-2));
    format = format.replace(/ss/g, ('0' + date.getSeconds()).slice(-2));
    if (format.match(/S/g)) {
        var milliSeconds = ('00' + date.getMilliseconds()).slice(-3);
        var length = format.match(/S/g).length;
        for (var i = 0; i < length; i++) format = format.replace(/S/, milliSeconds.substring(i, i + 1));
    }
    return format;
};