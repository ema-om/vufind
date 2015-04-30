<?php
return array(
  'extends' => 'bootstrap3',

  'less' => array(
    'active' => false,
    'compiled.less'
  ),

  'js'      => array(
    'jquery/ui/jquery-ui.min.js',

    'lib/jstorage.min.js', //used for favorites - there is still some amount of JS code inline of the page -> Todo: Refactoring in upcoming Sprints
    //wird in swissbib/AdvancedSearch.js verwendet
    'lib/handlebars.js',
    'lib/respond.js',
    'lib/html5shiv.js',

    //https://github.com/jquery/jquery-migrate/ brauchen wir das?
    'jquery/plugin/jquery-migrate-1.2.1.js',
    'jquery/plugin/jquery.base64.js',
    //brauchen wir das?
    //'jquery/plugin/jquery.easing.js',
    //'jquery/plugin/jquery.debug.js',
    //wurde im alten Node Layout verwendet
    //'jquery/plugin/colorbox/jquery.colorbox.js', //popup dialog solution
    'jquery/plugin/jquery.cookie.js',
    'jquery/plugin/jquery.spritely.js', // sprite animation, e.g. for ajax spinner
    //'jquery/plugin/jquery.validate.min.js',
    //'jquery/plugin/jquery.hoverintent.js',
    'jquery/plugin/loadmask/jquery.loadmask.js',

    '../themes/bootstrap3/js/vendor/jsTree/jstree.min.js',

    'swissbib/swissbib.js',

    'swissbib/AdvancedSearch.js',
    'swissbib/Holdings.js',
    'swissbib/HoldingFavorites.js',
    'swissbib/FavoriteInstitutions.js',
    'swissbib/Account.js',
    'swissbib/Settings.js',
    'swissbib/OffCanvas.js',
  ),

  'helpers' => array(
    'factories'  => array(
      'record'                    => 'Swissbib\View\Helper\Swissbib\Factory::getRecordHelper',
      'citation'                  => 'Swissbib\View\Helper\Swissbib\Factory::getCitation',
      'recordlink'                => 'Swissbib\View\Helper\Swissbib\Factory::getRecordLink',
      'getextendedlastsearchlink' => 'Swissbib\View\Helper\Swissbib\Factory::getExtendedLastSearchLink',
      'auth'                      => 'Swissbib\View\Helper\Swissbib\Factory::getAuth',
      'layoutClass'               => 'Swissbib\View\Helper\Swissbib\Factory::getLayoutClass',
      'searchtabs'                => 'Swissbib\View\Helper\Swissbib\Factory::getSearchTabs',
      'searchParams'              => 'Swissbib\View\Helper\Swissbib\Factory::getSearchParams',
      'searchOptions'             => 'Swissbib\View\Helper\Swissbib\Factory::getSearchOptions',
      'searchBox'                 => 'Swissbib\View\Helper\Swissbib\Factory::getSearchBox',
      'includeTemplate'           => 'Swissbib\View\Helper\Swissbib\Factory::getIncludeTemplate',
      'translateFacets'           => 'Swissbib\View\Helper\Swissbib\Factory::getFacetTranslator'
    ),
    'invokables' => array(
      'translate' => 'Swissbib\VuFind\View\Helper\Root\Translate',
    )
  )
);
