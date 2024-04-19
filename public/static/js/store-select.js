layui.config({
    base: '/static/lib/layui/extend/',
})
layui.use(['form',"okLayer","okUtils",'treeTable'],function(){
    var form = layui.form;
    var o = layui.$;
    let okUtils = layui.okUtils;
    let treeTable = layui.treeTable;
    var tree3 = treeTable.render({
        elem: '#tree-table1',
        data: [],
        icon_key: 'name',// 必须
        top_value: 0,
        primary_key: 'id',
        parent_key: 'pid',
        is_head: false,
        is_open: false,
        id: 'tree3',
        checked: {
            key: 'id',
            data: [1]
        },
        cols: [
            {
                key: 'name',
            },
            {
                align: 'right',
                template: function(item){
                    return '<input type="radio" name="pid" lay-filter="pid" value="'+item.id+'">';
                }
            }
        ],
        end: function(e){
            form.render()
        }
    })
    okUtils.ajax(store_category_list, "get", {}, true).done(function (response) {
        if (response.code === 0) {
            tree3.data = response.data
            treeTable.render(tree3);
        }
    }).fail(function (error) {
        console.log(error)
    });

    form.on('radio(pid)', function(data){
        var content = treeTable.parentValues(tree3, data.value, 0, tree3, []).join(' | ');
        o(data.elem).parents('.layui-form-select').find('.layui-select-title input').val(content);
        o('.layui-form-select').removeClass('layui-form-selected')
        o('#tree-table1').css('display','none')
    })
    o('.layui-select-title').click(function(){
        o(this).parent().hasClass('layui-form-selected') ? o(this).next().hide() : o(this).next().show(),o(this).parent().toggleClass('layui-form-selected');
    })
})