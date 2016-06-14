
<!-- main content-->
<div class="container" ng-controller="BreweryController">

	<div class="row" id="brewery">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div class="well text-center">
				<form name="brewerySearch" id="brewerySearch" class="form-horizontal well"  ng-submit="getBreweryByName(breweryName);" novalidate>

						<label for="breweryNameSearch">Search Breweries</label>
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-search"></i>
							</div>
							<input type="text" id="breweryNameSearch" name="breweryNameSearch" class="form-control" placeholder="search by brewery name" ng-model="breweryName"  ng-required="true" />
						</div>
						<div class="alert alert-danger" role="alert" ng-messages="brewerySearch.search.$error" ng-if="brewerySearch.search.$touched" ng-hide="brewerySearch.search.$valid">

							<p ng-message="required">Please enter your search.</p>
						</div>
						<h1></h1>
						<button class="btn btn-lg btn-info" type="submit"><i class="fa fa-search"></i>&nbsp;Find</button>
						<hr />
				</form>
			</div>
		</div>
	</div>

	<!--
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6 pull-left-md">
				<div class="well text-center">
				<a href="breweryprofile.php" ng-click="getBreweryProfile()">
					<h2>Name: {{ breweryData[0].breweryName }}</h2>
				</a>
				<h2>Location: {{ breweryData[0].breweryLocation }}</h2>
				<h2>Phone: {{ breweryData[0].breweryPhone }}</h2>
				<h2>URL: {{ breweryData[0].breweryURL }}</h2>
			</div>
		</div>
	</div>
	**/
	</div><!--container-->
<div class="row" id="brewerySearchTable">
	<table class="table table-bordered table-hover table-responsive table-striped table-word-wrap">
		<tr><th>Brewery Name</th><th>Location</th><th>Phone</th><th>URL</th></tr>
		<tr><td>
		<a href="breweryprofile.php" ng-click="getBreweryProfile()">
			{{ breweryData[0].breweryName }}</a></td>
			<td>{{ breweryData[0].breweryLocation }}</td>
			<td>{{ breweryData[0].breweryPhone }}</td>
			<td>{{ breweryData[0].breweryURL  }}</td>
		</tr>
	</table>