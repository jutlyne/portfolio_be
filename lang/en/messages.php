<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Message Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'success' => 'Get success.',
    'no_data' => 'No data.',
    'not_found' => 'Not found.',
    'create' => [
        'success' => 'Create success.',
        'failed' => 'Create failed.',
        'post_unauthorized' => 'You are not authorized to create this spread.',
    ],
    'update' => [
        'success' => 'Update success.',
        'failed' => 'Update failed.',
        'post_unauthorized' => 'You are not authorized to update this spread.',
        'media_not_found' => 'Media not found.',
        'content_required' => 'Content is required.',
        'invalid_selections' => 'Invalid selections.',
    ],
    'comment' => [
        'get_success' => 'Get list comment success.',
    ],
    'favorite' => [
        'success' => 'Save favorite success.',
        'failed' => 'Save favorite failed.',
    ],
    'unfavorite' => [
        'success' => 'Unsave favorite success.',
    ],
    'retweet' => [
        'success' => 'Boost success.',
        'failed' => 'Boost failed.',
    ],
    'donate' => [
        'success' => 'Donate successfully.',
        'failed' => 'Donate failed.',
        'disabled' => 'Donate is disabled.',
    ],
    'vote' => [
        'success' => 'Vote success.',
        'failed' => 'Vote failed.',
        'unvote' => 'Unvote success.',
        'selection_not_found' => 'Selection not found.',
        'outdated' => 'This selection is outdated.',
    ],
    'unretweet' => [
        'success' => 'Undo boost success.',
    ],
    'reaction' => [
        'success' => 'Save reaction success.',
        'failed' => 'Save reaction failed.',
    ],
    'unreaction' => [
        'success' => 'Unsave reaction success.',
    ],
    'upload' => [
        'success' => 'Upload success.',
        'failed' => 'Upload failed.',
    ],
    'send' => [
        'success' => 'Send success.',
        'failed' => 'Send failed.',
    ],
    'room' => [
        'unauthorized' => 'You are not authorized to access this room.',
    ],
    'post' => [
        'unauthorized' => 'You are not allowed to create spread donations.',
    ],
    'user' => [
        'not_found' => 'User not found.',
    ],
    'media' => [
        'unauthorized' => 'You are not authorized to access this media.',
    ],
    'delete' => [
        'success' => 'Delete success.',
        'failed' => 'Delete failed.',
        'post_unauthorized' => 'You are not authorized to delete this spread.',
    ],
    'pin' => [
        'success' => 'Pin success.',
        'failed' => 'Pin failed.',
        'unpinned' => 'Unpinned success.',
        'not_parent' => 'The selected spread is not a boost spread.',
        'post_unauthorized' => 'You are not authorized to pin this spread.',
    ],
    'change_status' => [
        'success' => 'Change status success.',
        'failed' => 'Change status failed.',
        'post_unauthorized' => 'You are not authorized to change status this spread.',
    ],
    'block' => [
        'retweet' => [
            'success' => 'Turn off boost success.',
            'failed' => 'Block boost failed.',
            'unblock' => 'Turn on boost success.',
            'post_unauthorized' => 'You are not authorized to boost this spread.',
        ],
        'mute' => [
            'success' => 'Mute user success.',
            'unmute' => 'Unmute user success.',
        ],
        'user' => [
            'success' => 'Block user success.',
            'unblock' => 'Unblock user success.',
            'failed' => 'Block failed.',
        ],
        'unauthorized' => 'Your account has no permissions'
    ],
    'community' => [
        'join' => 'Join the community successfully.',
        'out' => 'Leave the community successfully.',
        'owner_out' => 'Owners are not allowed to leave the community.',
        'not_owner' => 'You are not the owner of this community.',
        'not_jonned' => 'User has not joined the community.',
        'add_moderator' => 'Added a successful moderator.',
        'remove_moderator' => 'Remove a moderator successfully.',
        'not_in' => 'You do not belong to the community to spread.',
        'remove_user' => 'Remove a user successfully.',
        'remove_fail' => 'Remove fail.',
        'unauthorized' => 'Your account has no permissions',
        'not_join' => 'You can not join this community',
    ],
    'follow' => [
        'success' => 'Follow success.',
        'unauthorized' => 'You can not follow this account.',
    ],
    'unfollow' => [
        'success' => 'Unfollow success.',
        'unauthorized' => 'You can not unfollow this account.',
    ],
    'error' => 'Something went wrong.',
    'deleted' => [
        'success' => 'Account deleted successfully',
    ],
    'token' => [
        'invalid' => 'トークンコントラクト は無効です。',
    ],
];
