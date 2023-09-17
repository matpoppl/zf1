<?php

namespace App\Controller\Plugin;

use Zend_Controller_Request_Abstract as Request;

class MetaRoute extends AbstractPlugin
{
    public function routeStartup(Request $request)
    {
        /** @var \Zend_Controller_Request_Http $request */
        /** @var \App\Page\Metadata\Entity\PageMetaEntity $meta */
        $meta = $this->get('EntityManager')->getRepository('pagemetas')->findByUri($request->getRequestUri());

        if (null === $meta) {
            return;
        }

        $request->setRequestUri('page/page/view');
        $request->setModuleName('page');
        $request->setControllerName('page');
        $request->setActionName('view');
        $request->setParam('pageMeta', $meta);

        $request->setDispatched(true);

        return $request;
    }
}
