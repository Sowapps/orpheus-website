<?php
use Orpheus\Rendering\HTMLRendering;
use Orpheus\Config\AppConfig;

HTMLRendering::useLayout('page_skeleton');
?>
<h1>How to install Orpheus Framework ?</h1>

<p class="lead">
Orpheus PHP Framework comes with an easy-to-use installer allowing you to create an Orpheus project with a single line command.
You could also manually download the archive from GitHub.
</p>

<div class="alert alert-info" role="alert">
Orpheus Blossoming will come soon. For now, this is a WIP, expect some interesting changes.
</div>

<h2>Use the Installer Setup</h2>
<p>
This feature is using composer to direct download Orpheus with its dependencies.
</p>
<h4>Download the latest installer version</h4>
<p>First, <a href="<?php echo AppConfig::instance()->get('setup_latest_url'); ?>" target="_blank">download the latest phar of the installer</a> from github.</p>
<h4>Run the setup</h4>
<p>
In the parent folder of your new project, run the setup <code>php orpheus.phar install myprojectname</code>.<br>
Orpheus &amp; Composer will be installed in a subfolder named myprojectname.
</p>

<h2>Manual Download</h2>
<p>
You could also create a project by yourself, just download directly Orpheus.
</p>
<div class="row">
	<div class="col-sm-4">
		<h3 id="download-latest">The Latest</h3>
		<p>An archive of original sources, consider that Composer is not initialized.</p>
		<p><a href="<?php _u(ROUTE_DOWNLOAD_LATEST); ?>" class="btn btn-lg btn-success" target="_blank">Download Latest</a></p>
	</div>
	<div class="col-sm-4">
		<h3 id="browser-releases">The Releases</h3>
		<p>All releases, get the version you want by browsing our repos on GitHub.</p>
		<p><a href="<?php _u(ROUTE_DOWNLOAD_RELEASES); ?>" class="btn btn-lg btn-primary" target="_blank">Browse Releases</a></p>
	</div>
	<div class="col-sm-4">
		<h3 id="our-github">The GitHub</h3>
		<p>Directly browse the sources, you could clone it from GitHub.</p>
		<p><a href="<?php echo AppConfig::instance()->get('github_url'); ?>" class="btn btn-lg btn-info" target="_blank">See our GitHub</a></p>
	</div>
</div>

<h1>How to start with Orpheus Framework ?</h1>
<p class="lead">
The Orpheus Framework is entirely customizable but it started with MVC Library, an ORM and all features you need to get an enhanced website.
</p>

<h3>Understanding Orpheus</h3>
<p>
Orpheus separates different package features in namespaces and composer is helping you to maintain your application up-to-date by taking care of your dependencies.<br>
Web access point is /app/web and you will find some themes in it, each theme has its own css, js and layout files,
basic layout files are in PHP and the default theme is... default.<br>
You should put your own sources in /libs/src, you will find some sample here to help you to start.
</p>

<h3>Create your very first page</h3>
<p>
We are calling a route and access point to a controller, who is in charge to process the request and return a result, like a rendered view.
So, with Orpheus, you totally control how a user is getting in and what to return to him.
</p>
<div class="row">
	<div class="col-sm-4">
		<h4>1. Get a new Controller</h4>
		<p>Start by creating a Controller in your src folder, there is a HomeController class to help you.</p>	
	</div>
	<div class="col-sm-4">
		<h4>2. Draw a new template</h4>
		<p>Add your layout in the layout/app folder of your theme, all used template a relative to the layout folder.</p>	
	</div>
	<div class="col-sm-4">
		<h4>3. Link a new route</h4>
		<p>To get it online, create a route in the file configs/routes.yaml using instructions and examples we provided.</p>	
	</div>
</div>
<blockquote>This is so easy, you know.</blockquote>


