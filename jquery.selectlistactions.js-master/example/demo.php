
<?php

if (isset($_POST['submit'])) {
    # code...
    $c = $_POST['read_access'];
    echo $c[0];
}

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Select List Actions - jQuery Plugin</title>
  <meta name="description" content="Select List Actions - jQuery Plugin">

  <script src="js/jquery-1.10.2.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="js/jquery.selectlistactions.js"></script>
  
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/site.css">

  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
	<div class="container">
	
		<div class="row style-select">
		<form id="myform" method="POST" action="" data-toggle="validator" role="form">
			<div class="col-md-6">
				<label class="control-label">Superheroes</label>
				<select multiple class="form-control" id="StaffList" name="rd[]">
					<option value="123">Superman</option>
					<option value="456">Batman</option>
					<option value="789">Spiderman</option>
					<option value="654">Captain America</option>
				</select>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-3 add-btns">
						<input type="button" id="btnAvenger" value="Add Avenger" class="btn btn-default" />
					</div>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<label class="control-label">Avengers</label>
						<div class="selected-left">
							<select multiple class="form-control" id="PresenterList"  name="read_access[]">
								<option value="856">Iron Man</option>
							</select>
						</div>
						<div class="selected-right">
							<button type="button" class="btn btn-default btn-sm" id="btnAvengerUp">
								<span class="glyphicon glyphicon-chevron-up"></span>
							</button>
							<button type="button" class="btn btn-default btn-sm" id="btnAvengerDown">
								<span class="glyphicon glyphicon-chevron-down"></span>
							</button>
							<button type="button" class="btn btn-default btn-sm" id="btnRemoveAvenger">
								<span class="glyphicon glyphicon-remove"></span>
							</button>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-3 add-btns">
						<input type="button" id="btnShield" value="Add S.H.I.E.L.D." class="btn btn-default" />
					</div>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<label class="control-label">S.H.I.E.L.D.</label>
						<div class="selected-left">
							<select multiple class="form-control" id="ContactList" name="read_write">
								<option value="765">Nick Fury</option>
								<option value="698">The Hulk</option>
							</select>
						</div>
						<div class="selected-right">
							<button type="button" class="btn btn-default btn-sm" id="btnShieldUp">
								<span class="glyphicon glyphicon-chevron-up"></span>
							</button>
							<button type="button" class="btn btn-default btn-sm" id="btnShieldDown">
								<span class="glyphicon glyphicon-chevron-down"></span>
							</button>
							<button type="button" class="btn btn-default btn-sm" id="btnRemoveShield">
								<span class="glyphicon glyphicon-remove"></span>
							</button>
						</div>
						
					</div>
				</div>


			</div>
            <div class="form-group">
                        <input type="submit" name="submit" value="Add" class="btn btn-primary">
                        <!--<button type="submit" class="btn btn-primary">Next</button>-->
            </div>
			</form>
		</div>


	</div> <!--end of container -->
	
	<script>
        $('#btnAvenger').click(function (e) {
            $('select').moveToList('#StaffList', '#PresenterList');
            e.preventDefault();
        });

        $('#btnRemoveAvenger').click(function (e) {
            $('select').removeSelected('#PresenterList');
            e.preventDefault();
        });

        $('#btnAvengerUp').click(function (e) {
            $('select').moveUpDown('#PresenterList', true, false);
            e.preventDefault();
        });

        $('#btnAvengerDown').click(function (e) {
            $('select').moveUpDown('#PresenterList', false, true);
            e.preventDefault();
        });

        $('#btnShield').click(function (e) {
            $('select').moveToList('#StaffList', '#ContactList');
            e.preventDefault();
        });

        $('#btnRemoveShield').click(function (e) {
            $('select').removeSelected('#ContactList');
            e.preventDefault();
        });

        $('#btnShieldUp').click(function (e) {
            $('select').moveUpDown('#ContactList', true, false);
            e.preventDefault();
        });

        $('#btnShieldDown').click(function (e) {
            $('select').moveUpDown('#ContactList', false, true);
            e.preventDefault();
        });

        $('#btnJusticeLeague').click(function (e) {
            $('select').moveToList('#StaffList', '#FacilitatorList');
            e.preventDefault();
        });

        $('#btnRemoveJusticeLeague').click(function (e) {
            $('select').removeSelected('#FacilitatorList');
            e.preventDefault();
        });

        $('#btnJusticeLeagueUp').click(function (e) {
            $('select').moveUpDown('#FacilitatorList', true, false);
            e.preventDefault();
        });

        $('#btnJusticeLeagueDown').click(function (e) {
            $('select').moveUpDown('#FacilitatorList', false, true);
            e.preventDefault();
        });
		
        $('#btnRight').click(function (e) {
            $('select').moveToListAndDelete('#lstBox1', '#lstBox2');
            e.preventDefault();
        });

        $('#btnAllRight').click(function (e) {
            $('select').moveAllToListAndDelete('#lstBox1', '#lstBox2');
            e.preventDefault();
        });

        $('#btnLeft').click(function (e) {
            $('select').moveToListAndDelete('#lstBox2', '#lstBox1');
            e.preventDefault();
        });

        $('#btnAllLeft').click(function (e) {
            $('select').moveAllToListAndDelete('#lstBox2', '#lstBox1');
            e.preventDefault();
        });
    </script>
</body>
</html>