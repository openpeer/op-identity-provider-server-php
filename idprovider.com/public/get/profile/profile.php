<!-- 

Copyright (c) 2012, SMB Phone Inc.
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this
list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice,
this list of conditions and the following disclaimer in the documentation
and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

The views and conclusions contained in the software and documentation are those
of the authors and should not be interpreted as representing official policies,
either expressed or implied, of the FreeBSD Project.

-->


<?php 
    define('ROOT', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
    require_once (ROOT . 'config/config.php');
    require_once (ROOT . 'utils/cryptoUtil.php');
    require_once (ROOT . 'utils/profileUtil.php');
    require_once (ROOT . 'utils/jsonUtil.php');
	
    if (!isset($_GET['vprofile'])) {
        $_GET['vprofile'] = 0;
    }
    if (!isset($_GET['identifier'])) {
        print('Fatal: identifier is null');
        die();
    }
    
    $oResultObject = 
        ProfileUtil::sendProfileGet( 
            CryptoUtil::generateRequestId(), 
            $_GET['identifier'] );
    $aProfileInner = array (
        'uri'           => 'identity://' . DOMAIN . '/' . 
                            $oResultObject['identity']['identifier'],
        'displayName'   => $oResultObject['identity']['displayName'],
        'avatars'       => $oResultObject['identity']['avatars']
    );
    $aProfile = array ( 'profile' => $aProfileInner );
        
    if ( isset($_GET['vprofile']) && $_GET['vprofile'] ) {
        print('{'. JsonUtil::arrayToJson($aProfile) .'}'); die();
    }
    $aProfileHolder = array();
    
    if ( ( $oResultObject != null ) ) {
        if ( isset($oResultObject['request']['error']) ) {
            array_push($aProfileHolder, $oResultObject['error']);
        } else {
            array_push($aProfileHolder, $aProfile['profile']);
        }
    }
	
?>

<html>
<head>
<title>Example Identity Provider - Public Profile</title>
</head>
<body>
    <div id="profile">
        <div id="avatar">
            <img src="<?php echo $aProfile['profile']['avatars']['0']['url']; ?>"> <br/>
        </div>
        <div id="text">
            <p id="identifier">Identity URI = <?php echo $aProfile['profile']['uri']; ?></p> <br/>
            <p id="displayName">Display name = <?php echo $aProfile['profile']['displayName']; ?></p> <br/>
        </div>
    </div>
</body>
</html>