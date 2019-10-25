<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Calcul routier</title>
    <style>
        h1 {text-align: center;}
        button {margin:10px;}
    </style>
</head>
<body>
    
    <div>
        <div>
            <h1>Programme de calcul routier</h1>
        
            <form  method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <div>
                    <div>
                        <label for="city1">Depuis :</label><br>
                        <input type="text" name="city1" id="city1" >
                    </div>
                    <div >
                        <label for="city2">Jusqu'à :</label><br>
                        <input type="text" name="city2" id="city2" >
                    </div>
                    <div >
                        <button type="submit">Calculer</button>
                    </div>
                </div>
            </form>

        </div>

        <?php
        if(isset($_POST['city1']) && isset($_POST['city2']) && !empty($_POST['city1']) && !empty($_POST['city2'])) {
            $city1 = $_POST['city1']; $city2 = $_POST['city2'];
            $extract = file_get_contents('https://fr.distance24.org/route.json?stops=' . $_POST['city1'] . '|' . $_POST['city2']);
            $decode = json_decode($extract, true);
            $distance_totale = $decode['distance'];

            echo "<p>De ";
            echo $city1;
            echo " à ";
            echo $city2;
            echo ", la distance à parcourir est : ";
            echo $distance_totale; 
            echo " km. </p>";
            }
        ?>
        
    </div>
</body>
</html>

