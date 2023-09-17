<?php

namespace App\Menu\Entity;

use App\EntityManager\Repository\AbstractEntityRepository;

class MenuLinkRepository extends AbstractEntityRepository
{
    /** @var string */
    protected $entityClass = MenuLinkEntity::class;
 
    /**
     *
     * @param int $menu
     * @return int
     */
    public function getMaxWeight($menu)
    {
        return (int) $this->getDbTable()->queryColumn('MAX(weight)', [
            'parent=?' =>$menu,
        ]);
    }
    
    /**
     *
     * @param string $menu
     * @param int $parent
     * @return MenuLinkEntity[]
     */
    public function fetchByMenuSidParent($menu, $parent)
    {
        $select = $this->getDbTable()
        ->select(false)
        ->setIntegrityCheck(false)
        ->from(['ml' => 'menu_links'])
        ->join(['m' => 'menus'], 'm.id=ml.menu', '')
        ->where('m.sid=?', $menu)
        ->where('ml.parent=?', $parent)
        ->order('ml.weight DESC');
        
        return $this->getDbTable()->fetchAll($select);
    }
}
