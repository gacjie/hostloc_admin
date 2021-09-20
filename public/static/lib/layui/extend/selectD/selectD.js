layui.define(['layer', 'form'], function (exports) {
    var $ = layui.jquery;
    var layer = layui.layer;
    var form = layui.form;

    var selectD = {
        render: function () {
            selectList = document.querySelectorAll("[data-select]");
            $.each(selectList, function (i, v) {
                var url = $(this).attr('data-select'),
                    selectFields = $(this).attr('data-fields') || 'name',
                    value = $(this).attr('data-value'),
                    key = $(this).attr('data-key') || 'id',
                    that = this,
                    html = '<option value=""></option>';
                var fields = selectFields.replace(/\s/g, "").split(',');
                if (fields.length !== 1) {
                    return layer.msg('下拉选择字段有误', {icon: 2});
                }
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        selectFields: selectFields
                    },
                    dataType: "json",
                    success:function(res){
                        var list = res.data;
                        list.forEach(val => {
                            if (value !== undefined && val[key].toString() === value) {
                                html += '<option value="' + val[key] + '" selected="">' + val[fields[0]] + '</option>';
                            } else {
                                html += '<option value="' + val[key] + '">' + val[fields[0]] + '</option>';
                            }
                        });
                        $(that).html(html);
                        form.render();
                    },
                    error:function(jqXHR){
                        layer.msg("发生错误："+ jqXHR.status, {icon: 2});
                    }
                });
            });
        }
    };
    exports('selectD', selectD);
});