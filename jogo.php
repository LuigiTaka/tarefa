<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        
    #table{
        border:1px solid black;
    }
    #table td{
        width:50px;
        height:50px;
        border:1px solid black;
        text-align:center;
        background:white;
        text-transform: uppercase;
    }

    #table td.empty{
        background:silver;
        color:black;
    }

    body{
        background:gainsboro;
    }
    #table td:not(.empty):hover{
        background:oldlace;
        cursor:pointer;
    }
    </style>
    <title></title>
</head>
<body>

<table id="table">
    <tr>
        <?php for ($i=0; $i < 3; $i++) { 
            $random_number = intval( "0" . rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) ); // random(ish) 5 digit int

            $random_string = chr(rand(65,90)); // random(ish) 5 character string
            echo "<td>".$random_string."</td>";
        } ?>
    </tr>

     <tr>
       <?php for ($i=0; $i < 3; $i++) { 
            $random_number = intval( "0" . rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) ); // random(ish) 5 digit int

            $random_string = chr(rand(65,90)); // random(ish) 5 character string
            echo "<td>".$random_string."</td>";
        } ?>
    </tr>

     <tr>
        <?php for ($i=0; $i < 3; $i++) { 
            $random_number = intval( "0" . rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) ); // random(ish) 5 digit int

            $random_string = chr(rand(65,90)); // random(ish) 5 character string
            echo "<td>".$random_string."</td>";
        } ?>
    </tr>
</table>
</body>
</html>