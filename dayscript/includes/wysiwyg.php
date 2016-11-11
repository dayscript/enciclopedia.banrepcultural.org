<?php

wfLoadExtension( 'WikiEditor' );
//wfLoadExtension( 'WYSIWYG' );


//$wgGroupPermissions['*']['wysiwyg'] = true;
//$wgDefaultUserOptions['cke_show'] = 'richeditor';    //Enable CKEditor
$wgDefaultUserOptions['riched_use_toggle'] = false;  //Editor can toggle CKEditor/WikiText

$wgDefaultUserOptions['usebetatoolbar'] = 1;
//$wgDefaultUserOptions['usebetatoolbar-cgd'] = 1;
//$wgDefaultUserOptions['wikieditor-publish'] = 1;
//$wgDefaultUserOptions['wikieditor-preview'] = 1;

$wgDefaultUserOptions['jsbreadcrumbs-showcrumbs'] = true;
$wgDefaultUserOptions['jsbreadcrumbs-numberofcrumbs'] = 5;
$wgDefaultUserOptions['jsbreadcrumbs-showsite'] = false;
$wgDefaultUserOptions['jsbreadcrumbs-showcrumbssidebar'] = true;
$wgDefaultUserOptions['jsbreadcrumbs-pervasivewikifarm'] = false;
$wgDefaultUserOptions['jsbreadcrumbs-leading-description']= "Last Pages Viewed";
$wgJSBreadCrumbsSeparator = "&rarr;";
$wgJSBreadCrumbsCookiePath = "/";
$wgJSBreadCrumbsCSSSelector = "#top";
