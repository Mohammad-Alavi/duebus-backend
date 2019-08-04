<?php namespace Denora\Duebusbusiness\Classes\Repositories;

use Denora\Duebusbusiness\Models\SocialMedia;

class SocialMediaRepository {

    /**
     * @param int $socialMediaId
     *
     * @return SocialMedia
     */
    public function findById(int $socialMediaId) {
        return SocialMedia::find($socialMediaId);
    }

    /**
     * @param int    $businessId
     * @param string $instagram
     * @param string $facebook
     * @param string $linkedIn
     * @param string $youtube
     *
     * @return SocialMedia
     */
    public function createSocialMedia(
        int $businessId,
        string $instagram,
        string $facebook,
        string $linkedIn,
        string $youtube
    ) {

        $socialMedia = new SocialMedia();
        $socialMedia->business_id = $businessId;
        $socialMedia->instagram = $instagram;
        $socialMedia->facebook = $facebook;
        $socialMedia->linked_in = $linkedIn;
        $socialMedia->youtube = $youtube;

        $socialMedia->save();

        return $socialMedia;
    }

    /**
     * @param int   $socialMediaId
     * @param array $data
     *
     * @return SocialMedia
     */
    public function updateSocialMedia(int $socialMediaId, array $data) {

        $socialMedia = $this->findById($socialMediaId);

        if (array_has($data, 'business_id'))
            $socialMedia->business_id = $data['business_id'];
        if (array_has($data, 'instagram'))
            $socialMedia->instagram = $data['instagram'];
        if (array_has($data, 'facebook'))
            $socialMedia->facebook = $data['facebook'];
        if (array_has($data, 'linked_in'))
            $socialMedia->linked_in = $data['linked_in'];
        if (array_has($data, 'youtube'))
            $socialMedia->youtube = $data['youtube'];

        $socialMedia->save();

        return $socialMedia;
    }

    /**
     * @param int $socialMediaId
     *
     * @throws \Exception
     */
    public function deleteSocialMedia(int $socialMediaId) {
        $socialMedia = $this->findById($socialMediaId);
        $socialMedia->delete();
    }

}
