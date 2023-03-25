<?php require_once 'header.php';?>
<?php
error_reporting(E_ALL ^ E_WARNING);
require __DIR__.'/vendor/autoload.php'; // include Composer's autoloader
# phpinfo();

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->sem->news;

$result = $collection->find(); 

$start_index = 0;
$step = 30;

// get count of data in data base
$count_of_record = 0;
foreach($result as $item)
    $count_of_record++;

$result = $collection->find();

//print_r($count_of_record);

if (!empty($_GET))
{
    $start_index = isset($_GET['count']) ? $_GET['count'] : 0;

    if ($start_index < 0)
        $start_index = 0;
    elseif (($start_index + $step) > $count_of_record) 
        $start_index = $count_of_record - $step;


    // Поиск записи по ID
    if (isset($_GET['id']) ? $_GET['id'] : '' > 0)
    {
        $step = 1;
        $result = $collection->find();
        

        $i = 0;
        foreach($result as $item)
        {
            if ($item['_id'] == $_GET['id'])
                $start_index = $i;
            $i++;
        }

        $result = $collection->find(); 
    }
    
}

?>

<div class="container">
    <p class="py-2"> <a href="second.php">Статьи с обнаруженными именованными сущностями</a></p>
</div>

<!-- db data 1 -->
<div class="container">
    
    <p class="py-2"> Всего записей: <?php print_r($count_of_record) ?></p>


    <div class="container text-center mt-3">
    <?php #print_r($result);?>
    <?php if((is_array($result) || is_object($result)) ):?>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Номер</th>
                    <th scope="col">ID</th>
                    <th scope="col">Заголовок статьи</th>
                    <th scope="col">Дата публикации статьи</th>
                    <th scope="col">Ссылка на статью</th>
                    <th scope="col">Текст</th>
                    <th scope="col">Кол-во просмотров</th>
                </tr>
                </thead>

                <tbody>
                <?php $cnt = 0; foreach($result as $item):?>
                    <tr>
                        <?php if ($cnt >= $start_index && $cnt < $start_index + $step):?>
                            <td><?php print_r($cnt) ?></td>
                            <!-- <td><?php print_r($item['_id']) ?></td> -->
                            <td>
                                <form action="second.php" method="GET">
                                <div class="col-md-12 text-center ">
                                    <div class="form-group">
                                        <input type="text" hidden name="id" id="id" value="<?php print_r((string)$item['_id']) ?>"
                                        class="form-control">
                                        <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm login-btn">Именованные сущности</button>
                                    </div>
                                </div>
                                </form>

                            </td>
                            <td><?php print_r($item['title']) ?></td>
                            <td><?php print_r($item['date']) ?></td>  
                            <td><a href="<?php print_r($item['link']) ?>"><?php print_r($item['link']) ?></a></td>
                            <td><?php print_r($item['text']) ?></td>
                            <td><?php print_r($item['views']) ?></td>
                        <?php endif;?>
                <?php $cnt++; endforeach;?>
                </tbody>
            </table>
    <?php endif;?>
    </div>

</div>

<div class="row text-center " style="margin-bottom:20px;justify-content: center;">

<form action="index.php" method="GET" style="width:70px;margin-right:100px">
<div class="col-md-12 text-center" style="width:70px;">
    <div class="form-group">
        <input type="text" hidden name="count" id="count" value="<?php print_r($start_index - $step) ?>"
         class="form-control">
        <button type="submit" class=" btn btn-block mybtn btn-danger tx-tfm login-btn">Назад</button>
    </div>
</div>
</form>

<form action="index.php" method="GET" style="width:70px;">
<div class="col-md-12 text-center "style="width:70px;">
    <div class="form-group">
        <input type="text" hidden name="count" id="count" value="<?php print_r($start_index + $step) ?>" 
        class="form-control">
        <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm login-btn">Вперед</button>
    </div>
</div>
</form>
</div>

</body>
</html>

