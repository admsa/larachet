<?php namespace Admsa\Larachet\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Admsa\Larachet\Library\JsRenderer;

class AssetController extends Controller {
    /**
     * Render js scripts
     *
     * @return Illuminate\Http\Response
     */
    public function js(JsRenderer $js)
    {
        $response = Response(
            $js->getContents(), 200, array(
                'Content-Type' => 'text/javascript',
            )
        );

        // Cache response.
        $response->setSharedMaxAge(31536000);
        $response->setMaxAge(31536000);
        $response->setExpires(new \DateTime('+1 year'));

        return $response;
    }
}
