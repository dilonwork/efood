<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>1030 管理後台</title>
    <link href="<?php echo base_url('assets/inspinia_plugins/css/bootstrap.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/css/animate.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/css/plugins/sweetalert/sweetalert.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/css/style.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/css/plugins/iCheck/blue.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/css/plugins/select2/select2.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/Site.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/jexcel/css/jquery.jexcel.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/jexcel/css/jquery.jdropdown.css'); ?>" rel="stylesheet"  />

    <script src="<?php echo base_url('assets/inspinia_plugins/js/jquery-3.1.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/inspinia_plugins/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/jexcel/js/jquery.jexcel.js'); ?>"></script>
    <script src="<?php echo base_url('assets/jexcel/js/jquery.jdropdown.js'); ?>"></script>
</head>
<body>
    <h1>1030 管理後台 </h1>
    <div class="ibox">
        <div class="ibox-title">
            <h5>系統管理</h5>
            <div class="ibox-tools" style="float: right;">
                管理模組
                <select>
                    <option selected="true">餐廳品項管理</option>
                </select>
            </div>
        </div>
        <div class="ibox-content">
            <button type="button" class="btn btn-primary" id="btnAddRestaurant">新增餐廳</button>
            <button type="button" class="btn btn-primary" id="btnSynconize">資料同步</button>
            <BR /><BR />
            <div id="dataTable"></div>
            <?php //echo var_dump($food); ?>
        </div>
    </div>

    <div class="modal inmodal fade" id="mdlRDR1Detail" tabindex="-1" role="dialog" aria-hidden="true">
    <?php echo form_open('site/console/add'); ?>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span></button>
                    <h4 class="modal-title">新增餐廳</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label" >餐廳名稱</label>
                                <div class="col-sm-10"><input name="rt_name" type="text" class="form-control" required></div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">電話</label>
                                <div class="col-sm-10"><input name="rt_phone" type="text" class="form-control"></div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">地址</label>
                                <div class="col-sm-10"><input name="rt_address" type="text" class="form-control" ></div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">備註</label>
                                <div class="col-sm-10"><textarea  name="rt_comment" class="form-control" rows="5"></textarea></div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-sm">
                        <div class="col-lg-12">
                            <div class="panel blank-panel">
                                <div class="panel-heading">
                                    <div class="panel-options" id="divulLineNum">
                                        <ul class="nav nav-tabs"></ul>
                                    </div>
                                </div>

                                <div class="panel-body">
                                    <div class="tab-content" id="panelbody">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!--<button type="submit" class="btn btn-white" data-dismiss="modal" >新增</button>-->
                    <button type="submit" class="btn btn-white" >新增</button>
                </div>
            </div>
        </div>
        </form>
    </div>

    <script>
    var data = [    
    <?php foreach ($food as $food_item): ?>
        [
        '<?php echo $food_item->food_id; ?>',
        '<?php echo $food_item->food_name; ?>',
        '<?php echo $food_item->calories; ?>',
        '<?php echo $food_item->food_point; ?>',
        '<?php echo $food_item->six_grain; ?>',
        '<?php echo $food_item->six_meat; ?>',
        '<?php echo $food_item->six_vegatables; ?>',
        '<?php echo $food_item->six_oil; ?>',
        '<?php echo $food_item->six_fruit; ?>',
        '<?php echo $food_item->six_milk; ?>',
        '<?php echo $food_item->other_sugars; ?>',
        '<?php echo $food_item->vegetarianOvoLacto; ?>',
        '<?php echo $food_item->vegetarianFiveForbidden; ?>',
        '<?php echo $food_item->vegetarian; ?>',
        '<?php echo $food_item->key_word; ?>',
        '<?php echo $food_item->restaurant_id; ?>'],
    <?php endforeach; ?>
    ];

    var restaurants =[
    <?php foreach ($restaurant as $restaurant_item): ?>
    { 'id':'<?php echo $restaurant_item->id;?>','name':'<?php echo $restaurant_item->name;?>'},
    <?php endforeach; ?>
    ];

    $(function ()
    {
        GenData();

        $( "#btnAddRestaurant" ).button().on( "click", function() 
        {
           $('#mdlRDR1Detail').modal();
        });
    });

    function GenData()
    {
        var update = function(instance, cell, value) 
        {
            //var update = function (obj, cel, val) 
            // Get the cell position x, y
            /*
            var id = $(cel).prop('id').split('-');
            console.log('[UPDATE], 餐廳編號:'+data[id[0]][0]+', 食物編號:'+data[id[0]][1]+', 食物名稱:'+data[id[0]][2]+', 異動欄位:'
                +'('+id[15]+','+id[0]+'), 異動值:'+val);
            */
            var cellName = $(instance).jexcel('getColumnNameFromId', $(cell).prop('id'));
            console.log('$(cell).prop(id):'+$(cell).prop('id'));

            var id = $(cell).prop('id').split('-');
            console.log('食物編號:'+ data[id[1]][0]);
            console.log('食物名稱:'+ data[id[1]][1]);
            console.log('食物資訊:'+ data[id[1]]);
            $.ajax({
                type:"POST",
                url: "<?php echo base_url('index.php/site/console/syncRestaurantFood') ?>",
                contentType: "application/json",
                dataType: 'json',
                async: false,
                cache: false,
                //data: { emp_ename: emp_ename, ststat: ststat },
                data: { food_id: data[id[1]][0], food_data: data[id[1]]},
                success: function (message) {
                    console.log(message)

                },
                error: function () { alert('讀取 發生錯誤'); }

            });
            console.log('New change on cell ' + cellName + ' to: ' + value + '<br>');
        }

        dropdown = function(instance, cell, c, r, source) 
        {
            // Get a value from the same row but previous column
            var value = $(instance).jexcel('getValue', c-1 + '-' + r);
            /*
            // Return the values will be part in your current column
            if (value == 1) {
                return ['Apples','Bananas','Oranges'];
            } else if (value == 2) {
                return ['Carrots'];
            } else {
                return source;
            }
            */
        }

        $('#dataTable').jexcel({
            data:data,
            colHeaders: ['食物編號', '食物名稱', '卡路里', '食點', '穀類', '肉類', '蔬菜類', '油脂', '水果類', '乳品','糖類', '奶蛋素', '五辛素', '純素', '關鍵字','餐廳'],
            colWidths: [ 60, 180, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 60, 120,120],
            onchange:update,
            columns: [
                    { type: 'number', readOnly:true },
                    { type: 'text'},
                    { type: 'number'},{ type: 'number'},
                    { type: 'number'},{ type: 'number'},
                    { type: 'number'},{ type: 'number'},
                    { type: 'number'},{ type: 'number'},
                    { type: 'number'},{ type: 'number'},
                    { type: 'number'},{ type: 'number'},
                    { type: 'number'},
                    { type: 'dropdown', source: restaurants}]
            //columns: [
            //{ type: 'dropdown', source: [ {'id':'1', 'name':'Fruits'},  {'id':'2', 'name':'Legumes'}, {'id':'3', 'name':'General Food'} ] },
            //{ type: 'dropdown', source: [ 'Apples', 'Bananas', 'Carrots', 'Oranges', 'Cheese' ], filter:dropdown },
            //{ type: 'checkbox' },
            //]
        });
    }

    </script>
</body>

</html>
