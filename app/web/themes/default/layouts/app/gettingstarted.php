<?php
use Orpheus\Rendering\HTMLRendering;
use Orpheus\Config\AppConfig;

HTMLRendering::useLayout('page_skeleton');
?>
<h1>How to install Orpheus Framework ?</h1>

<p class="lead">
Orpheus PHP Framework comes with an easy-to-use installer allowing you to create an Orpheus project with a single line command.
You could also directly download the archive from GitHub.
</p>

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
		<p>All releases, get the version you want byt browsing it on GitHub.</p>
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

