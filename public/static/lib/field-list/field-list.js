layui.define(['jquery'], function (exports) {
    var $ = layui.jquery;
    var instacnces = {};
    var FieldList = function (id, config) {
        this.v = '1.0.0';
        this.el = config.el;
        this.index = 0;
        this.config = config;
        $(this.el).hide();
        this.dl = $("<dl></dl>");
        this.dl.addClass("fieldlist").attr('data-name', this.config.name).attr('data.listlength', this.index);
        this.dl.append('<dd><ins>键名</ins><ins>键值</ins></dd>')

        $(this.dl).append('<button type="button" class="layui-btn layui-btn-sm field-add"><i class="layui-icon layui-icon-addition"></i>添加</button>');
        $(this.el).parent().append(this.dl)
        let self = this;
        $(this.dl).on("click", "button", function () {
            if ($(this).hasClass("field-add")) {
                self.append();
            } else if ($(this).hasClass("field-remove")) {
                if($(self.dl).find('dd').length==2){
                console.log("没有了")
                   return
                }
                $(this).parents("dd").remove();
            }else if ($(this).hasClass("field-move")) {
                $(this).css("")
            }
           
        })
        function _index(el) {
            var index = 0;
            if (!el || !el.parentNode) {
                return -1;
            }
            while (el && (el = el.previousElementSibling)) {
                index++;
            }
            return index;
        }
        function switchItem(draging,source,target){
            source.parentNode.insertBefore(draging, target);
            //修改index
            let sourceIndex = $(source).attr('data-index');
            let dragingIndex = $(draging).attr('data-index');
            $(source).find(".filed-key").attr('name', self.config.name + '[' +dragingIndex + '][key]')
            $(source).find(".filed-value").attr('name', self.config.name + '[' +dragingIndex + '][value]')
            $(source).attr('data-index',dragingIndex);
            $(draging).attr('data-index',sourceIndex);
            $(draging).find(".filed-key").attr('name', self.config.name + '[' +sourceIndex + '][key]')
            $(draging).find(".filed-value").attr('name', self.config.name + '[' +sourceIndex + '][value]')
        }
        var draging = null;

        $(this.dl).on('dragstart','dd',function(event){
            // event.dataTransfer.setData("te", event.target.innerText);
            draging =event.target;
        })
        $(this.dl).on('dragover','dd',function(event){
            event.preventDefault();
            var target = event.target;
            //因为dragover会发生在ul上，所以要判断是不是li
                if (target.nodeName === "DD"&&target !== draging && $(target).is('[data-index]')) {
                        //_index是实现的获取index              
                        if (_index(draging) < _index(target)) {
                            switchItem(draging,target,target.nextSibling)
                        } else {
                            switchItem(draging,target,target)
                        }
                }
        })
        if(!this.config.data || this.config.data.length==0){
            this.config.data = [
                {key:'',value:''}
            ]
        }
        if (this.config.data) {
            this.appendAll(this.config.data)
        }
    };
    FieldList.prototype.appendAll = function (data) {
        for(var item of data){
            this.append(item.key,item.value)
        }
    }
    FieldList.prototype.append = function (key,value) {
        var dd = $("<dd></dd>");
        $(dd).attr('draggable','true').attr("data-index",this.index);
        $(dd).append('<input type="text" name="' + this.config.name + '[' + this.index + '][key]"  size="10" class="layui-input filed-key" lay-verify="required" value="'+(key||"")+'"/>')
        $(dd).append('<input type="text" name="' + this.config.name + '[' + this.index + '][value]"  size="10" class="layui-input filed-value" lay-verify="required" value="'+(value||"")+'" style="margin-left:5px"/> ')
        $(dd).append('<button type="button" class="layui-btn layui-btn-sm field-remove"><i class="layui-icon layui-icon-close"></i></button>')
        $(dd).append('<button type="button" class="layui-btn layui-btn-sm field-move"><i class="layui-icon layui-icon-slider"></i></button>')
        $(this.dl).find(".field-add").before(dd);
        this.index++;
        $(this.dl).attr('data.listlength', this.index);
    }

    exports('FieldList', FieldList);
});