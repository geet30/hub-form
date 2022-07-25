<?php
/**
 * @var Firebase Services
 */
namespace App\Services;

use Kreait\Firebase\DynamicLink\CreateDynamicLink\FailedToCreateDynamicLink;
use Kreait\Firebase\Factory;

class Firebase
{

    public $firebaseObject;

    /**
     * @var initailize firebase object
     */
    public function __construct()
    {
        $serviceAccount = base_path(config('firebase.credentials.file'));
        $this->firebaseObject = (new Factory)->withServiceAccount($serviceAccount);
    }

    /**
     * Create Deep link
     */
    public function createDynamicLink($url)
    {
        try {
            $dynamicLinksDomain = env("FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN");
            $dynamicLinks = $this->firebaseObject->createDynamicLinksService($dynamicLinksDomain);
            $parameters = [
                'dynamicLinkInfo' => [
                    'domainUriPrefix' => $dynamicLinksDomain,
                    'link' => $dynamicLinksDomain.'/'.$url,
                    'androidInfo' => [
                        'androidPackageName' => 'com.budgetpurchase'
                    ]
                ],
                'suffix' => ['option' => 'UNGUESSABLE'],
            ];
            $link = $dynamicLinks->createDynamicLink($parameters);
            return $link->uri();
        } catch (FailedToCreateDynamicLink $e) {
            // echo $e->getMessage();exit;
            return false;
        }
    }
}
