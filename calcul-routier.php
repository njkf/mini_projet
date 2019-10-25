<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Calcul routier</title>
    <style>
        h1 {text-align: center;}
        button {margin:10px;}
        tr {padding-right:50px;}
    </style>
</head>
<body>
    
    <div>
        <div>
            <h1>Programme de calcul routier </h1>
        
            <form  method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                <div>
                    <div>
                        <label for="city1">De :</label><br>
                        <input type="text" name="city1" id="city1" >
                    </div>
                    <div >
                        <label for="city2">A :</label><br>
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

            $distance_restante = $distance_totale;
            $temps_total = 0;
            $vitesse_moyenne = 90;
            $vitesseMoy_accel_frein = (10+20+30+40+50+60+70+80+90)/9; // vitesse moyenne les 9 premieres et dernieres minutes = 50km/h
            $distance_accel_frein = (18/60)*$vitesseMoy_accel_frein; // distance d'acceleration et de freinage = 15km
            $distance_sans_pause = ((120-18)/60)*90; //distance parcourue sans l'acceleration et le freinage = 153km
            $distance_avec_pause = $distance_sans_pause + $distance_accel_frein; //distance parcourue en 2h de route + 15 min de pause = 168km

            while ($distance_restante > 0) {  
                if ($distance_restante == $distance_avec_pause) {
                    $temps_total += 120;
                    $distance_restante -= $distance_avec_pause;     
                }
                else if ($distance_restante < $distance_avec_pause) {
                    $distance_restante -= 15;
                    $temps_total += (($distance_restante/90)*60) + 18;
                    $distance_restante = 0;
                }
                else {
                    $temps_total += 135; //2h + 15 min de pause 
                    $distance_restante -= $distance_avec_pause;
                }
            }
            
            echo "<p>De ";
            echo $city1;
            echo " à ";
            echo $city2;
            echo ", la distance à parcourir est : ";
            echo $distance_totale; 
            echo " km. </p>";
            echo "<p>Le trajet se fait en ";
            echo date("G:i", $temps_total);
            echo " min. </p>";
            }
        ?>

    <table>
        <thead>
            <tr>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Distance</th>
                <th>Temps</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_POST['city1']) && isset($_POST['city2']) && !empty($_POST['city1']) && !empty($_POST['city2'])) {
                ?>
                <tr>
                    <td><?php echo $city1;?></td>
                    <td><?php echo $city2;?></td>
                    <td><?php echo $distance_totale;?> km</td>
                    <td><?php echo date("G:i", $temps_total);?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

        
    </div>
</body>
</html>

