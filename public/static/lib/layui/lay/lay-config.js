/**
 * date:2019/08/16
 * author:Mr.Chung
 * description:此处放layui自定义扩展
 * version:2.0.4
 */

window.rootPath = (function (src) {
    src = document.scripts[document.scripts.length - 1].src;
    return src.substring(0, src.lastIndexOf("/") + 1);
})();

layui.config({
    base: rootPath + "modules/",
    version: true
}).extend({
    treetable: 'treetable-lay/treetable', //table树形扩展
	iconPickerFa: 'iconPicker/iconPickerFa', // fa图标选择扩展
});
