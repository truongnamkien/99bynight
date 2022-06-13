<?php use Cake\Core\Configure; ?>
<?php $longtitude = !empty($siteLocation['longtitude']) ? $siteLocation['longtitude'] : Configure::read('GoogleMap.DefaultCoordinate.Longtitude'); ?>
<?php $latitude = !empty($siteLocation['latitude']) ? $siteLocation['latitude'] : Configure::read('GoogleMap.DefaultCoordinate.Latitude'); ?>
<?php $this->loadHelper('Content'); ?>
<?php echo $this->element('breadcrumb'); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Configure::read('GoogleMap.ApiKey'); ?>"></script>
<div class="section mt-0">
    <div class="contact-map" id="googleMapContact" data-lng="<?php echo $longtitude; ?>" data-lat="<?php echo $latitude; ?>"></div>
</div>
<script type="text/javascript">
    google.maps.event.addDomListener(window, 'load', init);
    function init() {
        var mapCenter = {
            lat: <?php echo $latitude; ?>,
            lng: <?php echo $longtitude; ?>,
        };
        var mapOptions = {
            zoom: 18,
            center: mapCenter,
            scrollwheel: true,
            scaleControl: true,
            disableDefaultUI: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById('googleMapContact'), mapOptions);
        var marker = new google.maps.Marker({
            position: mapCenter,
            icon: '/img/icon-gm-marker.png',
            map: map
        });
        var headerMap = new google.maps.Map(document.getElementById('googleMapDrop'), mapOptions);
        var headerMarker = new google.maps.Marker({
            position: mapCenter,
            icon: '/img/icon-gm-marker.png',
            map: headerMap
        });
        <?php if (!empty($siteAddress)): ?>
            var infoWindow = new google.maps.InfoWindow();
            var $html = '<div class="map-info"><h5><?php echo PAGE_TITLE; ?></h5>';
            $html += '<p><?php echo $siteAddress; ?></p>';
            $html += '</div>';
            infoWindow.setContent($html);
            infoWindow.open(map, marker);
            infoWindow.open(headerMap, headerMarker);
        <?php endif; ?>
        google.maps.event.addDomListener(window, 'resize', function () {
            map.setCenter(mapCenter);
            headerMap.setCenter(mapCenter);
        })
    }
</script>
<div class="section">
    <div class="container">
        <div class="text-center mb-2  mb-md-3 mb-lg-4">
            <h1><?php echo __('Contact'); ?></h1>
            <div class="h-decor"></div>
        </div>
    </div>
</div>
<div class="section mt-0 bg-grey">
    <div class="container">
        <div class="row">
            <div class="col-md">
                <div class="title-wrap">
                    <h5><?php echo PAGE_TITLE; ?></h5>
                    <div class="h-decor"></div>
                </div>
                <ul class="icn-list-lg">
                    <?php if (!empty($siteAddress)): ?>
                        <li>
                            <i class="icon-placeholder2"></i>
                            <?php echo $siteAddress; ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md mt-3 mt-md-0">
                <div class="title-wrap">
                    <h5><?php echo __('Contact Info'); ?></h5>
                    <div class="h-decor"></div>
                </div>
                <ul class="icn-list-lg">
                    <?php if (!empty($sitePhone)): ?>
                        <li>
                            <i class="icon-telephone"></i>
                            <span class="theme-color">
                                <span class="text-nowrap"><?php echo $sitePhone; ?></span>
                            </span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($siteEmail)): ?>
                        <li>
                            <i class="icon-black-envelope"></i>
                            <a href="mailto:<?php echo $siteEmail; ?>"><?php echo $siteEmail; ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md mt-3 mt-md-0">
                <div class="title-wrap">
                    <h5><?php echo __('Working Time'); ?></h5>
                    <div class="h-decor"></div>
                </div>
                <ul class="icn-list-lg">
                    <li>
                        <i class="icon-clock"></i>
                        <div>
                            <?php if (!empty($workingMonFri)): ?>
                                <div class="d-flex">
                                    <span><?php echo __('Mon - Fri'); ?></span>
                                    <span class="theme-color"><?php echo nl2br($workingMonFri); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($workingSat)): ?>
                                <div class="d-flex">
                                    <span><?php echo __('Sat'); ?></span>
                                    <span class="theme-color"><?php echo nl2br($workingSat); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($workingSun)): ?>
                                <div class="d-flex">
                                    <span><?php echo __('Sun'); ?></span>
                                    <span class="theme-color"><?php echo nl2br($workingSun); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="section">
    <form enctype="multipart/form-data" class="contact-form" id="contactForm" method="post" name="contact" rel="async" action="<?php echo $this->Url->build('contact/submit', true); ?>">
        <div class="container">
            <div class="row">
                <div class="col-md col-lg-6">
                    <div>
                        <input class="form-control" id="fullname" type="text" name="fullname" placeholder="<?php echo __('Your Name (*)'); ?>" />
                    </div>
                    <div class="mt-15">
                        <input class="form-control" id="email" type="email" name="email" placeholder="<?php echo __('Your Email (*)'); ?>" />
                    </div>
                    <div class="mt-15">
                        <input class="form-control" id="phone" type="text" name="phone" placeholder="<?php echo __('Your Phone (*)'); ?>" />
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-hover-fill">
                            <i class="icon-right-arrow"></i>
                            <span><?php echo __('Send message'); ?></span>
                            <i class="icon-right-arrow"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md col-lg-5 mt-4 mt-md-0">
                    <textarea class="form-control" name="content" id="content" placeholder="<?php echo __('Your Message (*)'); ?>"></textarea>
                </div>
            </div>
        </div>
    </form>
</div>