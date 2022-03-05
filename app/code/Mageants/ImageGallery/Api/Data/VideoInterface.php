<?php
/**
 * @category Mageants ImageGallery
 * @package Mageants_ImageGallery
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ImageGallery\Api\Data;

interface VideoInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const VIDEO_ID      = 'video_id';
    const VIDEO_TITLE   = 'video_title';
    const VIDEO   = 'video';
    const IS_ACTIVE     = 'is_active';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Image title
     *
     * @return string
     */
    public function getVideoTitle();
    
    public function getVideo();
    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Mageants\ImageGallery\Api\Data\ImageInterface
     */
    public function setId($id);

    /**
     * Set Image title
     *
     * @param string $image_title
     * @return \Mageants\ImageGallery\Api\Data\ImageInterface
     */
    public function setVideoTitle($video_title);

    public function setVideo($video);
    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Mageants\ImageGallery\Api\Data\ImageInterface
     */
    public function setIsActive($isActive);
}
