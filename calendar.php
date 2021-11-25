<?php

/*

Да се създаде работещ календар. За целта трябва да бъдат изпълнени следните условия:

1. При избран месец от падащото меню и попълнена година в полето - да се визуализира календар за въпросните месец и година
2. Ако не е избран месец или година, да се използват текущите (пример: ноември, 2021)
3. Месецът и годината, за които е показан календар да са попълнени в падащото меню и полето за година
3. При натискане на бутон "Today" да се показва календар за текущите месец и година
5. В първия ред на календара да има:
  1. Стрелка на ляво, която да показва предишния месец при кликване
  2. Текст с името на месеца и годината, за които са показани дните
  3. Стрелка в дясно, която да показва следващия месец при кликване
6. Таблицата да показва дни от предишния и/или следващия месец до запълване на седмиците (пример: 
 * Ако месеца започва в сряда, да се покажат последните два дни от предишния месец за вторник и понеделник)
7. Показаните дни в таблицата трябва да са черни и удебелени за текущия месец, и сиви за предишен или 
 * следващ месец (css клас "fw-bold" за текущия месец и "text-black-50" за останалите)

*/
// your code here...
 

$Cells = array(); //Масив за визуализиране на клетките с дати
$m = 11;
$y = 2021;
$d = cal_days_in_month(CAL_GREGORIAN,$m,$y); // Брой дни за избрания месец

$months = array(
                    1=>'January',
                    2=>'February', 
                    3=>'March', 
                    4=>'April',
                    5=> 'May', 
                    6=>'June', 
                    7=>'July', 
                    8=>'August', 
                    9=>'September', 
                    10=>'Octomber',
                    11=>'November',
                    12=> 'December');


if(isset($_GET['m']) && isset($_GET['y']))
{
    
    $y = $_GET['y'];
    $m = $_GET['m'];
    $d = cal_days_in_month(CAL_GREGORIAN, $m, $y);
    
}
$pmd = 0;
if($m == 1)
{
    $pmd = cal_days_in_month(CAL_GREGORIAN,12,$y-1); // Дните от предишния месец    
}
else{
$pmd = cal_days_in_month(CAL_GREGORIAN,$m-1,$y); // Дните от предишния месец
}
$dayOfWeek = date('w', strtotime($y.'-'.$m.'-1')); // Първият ден от месеца какъв ден от седмицата е
$dayOfWeek--; // Изваждаме 1, за да започва от понеделник


if($dayOfWeek == -1) // Условие, за да преместим неделя в края на таблицата
{
	$dayOfWeek = 6;
}

$pd = $pmd-$dayOfWeek; // Последните дни на предишния месец, които ще се визуализират
for($i=0;$i<$dayOfWeek; $i++)
{
     $pd++; 
     $Cells[] = $pd;

}

$countFirstDays = count($Cells); // Брои клетките, заети за дните на предишен месец(необходима за промяна на цвета)

for($i=1; $i<=$d; $i++) // Запълваме дните от избрания месец
{
	$Cells[] = $i;
}


if(count($Cells) < 42)// Допълва първите дни на следващия месец
{
	for($i=0;$i<=42-($d+$dayOfWeek);$i++)
	{
		$Cells[] = $i+1;
	}
}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Calendar</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col">
          <h1>Calendar</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 offset-md-3 col-lg-6 offset-lg-3">
          <form class="row g-3">
            <div class="col-md-6 col-lg-6">
              <label class="form-label" for="month">Select month</label>
              <select name="m" class="form-control" id="month">
                <?php 
                foreach($months as $number => $value) // Месеците от падащото меню
                {
                  if($number == $m) // Избрания месец да е selected
                    {                         
                     echo ' <option selected value="'.$number.'">'.$value.'</option>';
                      
                    }
                    else{
                    
                    echo '<option  value="'.$number.'">'.$value.'</option>';
                  
                    }
                }
              
                ?>
              </select>
            </div>
            <div class="col-md-6 col-lg-6">
              <label class="form-label" for="year"> Year: </label>
              <input type="text" name="y" class="form-control" value="<?php echo $y; ?>">
            </div>
            <div class="col-md-12 col-lg-12">
              <button type="submit" class="btn btn-primary">Show</button>
              <a href="<?php echo '?m='.date('m').'&y='.date('Y'); ?>" class="btn btn-secondary">Today</a>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mt-5 offset-md-3 col-lg-6 offset-lg-3">
          <table class="table table-bordered text-center">
            <thead>
              <tr>
                <th>
                  <a href="?m=<?php 
                  
                  if($m == 1 && $m > 0){ // Проверка дали е избран януари, за да визуализира дните от декември на предишната година
                      echo '12&y='.$y-1;
                  }else { echo $m-1;echo'&y='.$y; }
                  ?>">&larr;</a>
                </th>
                <th colspan="5" class="text-center"><?php echo date('F', mktime(0, 0, 0, $m, 10, $y)).', '.$y; ?></th>
                <th>
                  <a href="?m=<?php 
                  
                  if ($m == 12){ // Проверка дали е избран декември, за да визуализира следващата година(януари)
                      echo '1&y='.$y+1;
                  }else{ echo $m++.'&y='.$y; }
                      ?>"title="Next month">&rarr;</a>
                </th>
              </tr>
              <tr>
                <th>Mon</th>
                <th>Tue</th>
                <th>Wed</th>
                <th>Thu</th>
                <th>Fri</th>
                <th>Sat</th>
                <th>Sun</th>
              </tr>
            </thead>
            <tbody>
              <!-- remove the following and add your code to display the days: -->
              <?php
              
              for($i = 1; $i<=42; $i++) // Постояваме календара
              {
                  if($i%7 == 1) // Да отваря ред, когато започва нова седмица
                   {
                      echo "<tr>";
                       
                   }
                 
                   if($i<=$dayOfWeek || $i>$d+$countFirstDays) // по-светлите дни, които са от предишен и следващ месец
                   {
                       ?>
                       <td class="text-black-50"><?php echo $Cells[$i-1]; ?></td>
                       <?php
                       
                   }
                  else{
                 ?>
                  <td class="fw-bold"><?php echo $Cells[$i-1]; ?></td>
                  <?php
                   }
                   if($i % 7 === 0)
                   {
                       echo "</tr>"; 
                   }
               }
               ?>
            
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>