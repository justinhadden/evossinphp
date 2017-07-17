<!doctype html>
<html lang"en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EVOSS - EE</title>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/theStyles.css">
	<script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
    <script src="js/myjs.js"></script>
</head>
<body>
<div class="centered">
    <h1>OT Generation Criteria</h1>
</div> 

<form action="?" method="post">
    <h2 class="centered">Datepicker</h2>
    <div class="centered form-group">
        <input id="datepicker" name="ssdate"></input>
    </div>

    <h2 class="centered">Selectmenu</h2>
        <div class="centered">
            <select id="selectmenu" name="ssjobcode">
                <optgroup label="Forming">
                    <option value="FHB">FHB</option>
                    <option value="WIO">WIO</option>
                    <option value="MPO">MPO</option>
                    <option value="FPO">FPO</option>
                    <option value="ABS">ABS</option>
                    <option value="PSU">PSU</option>
                    <option value="S2S">S2S</option>
                    <option value="FSE">FSE</option>
                    <option value="CPS">CPS</option>
                    <option value="BAO">BAO</option>
                </optgroup>
                <optgroup label="Fabrication">
                    <option value="ABE">ABE</option>
                    <option value="CLR">CLR</option>
                    <option value="FCL">FCL</option>
                    <option value="ABM">ABM</option>
                    <option value="EFO">EFO</option>
                    <option value="FOH">FOH</option>
                    <option value="RSM">RSM</option>
                    <option value="FEH">FEH</option>
                    <option value="FSR">FSR</option>
                    <option value="FPA">FPA</option>
                    <option value="TSS">TSS</option>
                </optgroup>
                <optgroup label="Special Fab">
                    <option value="CLR">CLR</option>
                    <option value="CRF">CRF</option>
                    <option value="SFU">SFU</option>
                    <option value="WOP">WOP</option>
                    <option value="IMX">IMX</option>
                </optgroup>
                <optgroup label="Maintenance">
                    <option value="HMU">HMU</option>
                    <option value="LRC">LRC</option>
                    <option value="MMM">MMM</option>
                    <option value="UEP">UEP</option>
                    <option value="ELE">ELE</option>
                    <option value="WWT">WWT</option>
                    <option value="PGM">PGM</option>
                    <option value="CAT">CAT</option>
                </optgroup>
                <optgroup label="Logistics">
                    <option value="WAC">WAC</option>
                    <option value="RWI">RWI</option>
                    <option value="STK">STK</option>
                    <option value="WAA">WAA</option>
                </optgroup>
                <optgroup label="Quality">
                    <option value="PQT">PQT</option>
                </optgroup>			
            </select>
        </div>

    <h2 class="centered">Shift Number</h2>
    <div class="centered">
        <select id="selectmenu" name="ssshiftnum">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
    </div>
    <div class="form-group centered">
        <input class="btn btn-primary btn-lg" type="submit" value="Submit">
    </div>
</form>
</body>
</html>