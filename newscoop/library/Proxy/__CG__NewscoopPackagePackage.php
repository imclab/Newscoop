<?php

namespace Proxy\__CG__\Newscoop\Package;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Package extends \Newscoop\Package\Package implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function __toString()
    {
        $this->__load();
        return parent::__toString();
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setHeadline($headline)
    {
        $this->__load();
        return parent::setHeadline($headline);
    }

    public function getHeadline()
    {
        $this->__load();
        return parent::getHeadline();
    }

    public function getItems()
    {
        $this->__load();
        return parent::getItems();
    }

    public function setRendition(\Newscoop\Image\Rendition $rendition)
    {
        $this->__load();
        return parent::setRendition($rendition);
    }

    public function getRendition()
    {
        $this->__load();
        return parent::getRendition();
    }

    public function getPrev(\Newscoop\Package\Item $currentItem)
    {
        $this->__load();
        return parent::getPrev($currentItem);
    }

    public function getNext(\Newscoop\Package\Item $currentItem)
    {
        $this->__load();
        return parent::getNext($currentItem);
    }

    public function setSlug($slug)
    {
        $this->__load();
        return parent::setSlug($slug);
    }

    public function getSlug()
    {
        $this->__load();
        return parent::getSlug();
    }

    public function setItemsCount($count)
    {
        $this->__load();
        return parent::setItemsCount($count);
    }

    public function getItemsCount()
    {
        $this->__load();
        return parent::getItemsCount();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'headline', 'description', 'slug', 'items', 'rendition', 'articles');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}