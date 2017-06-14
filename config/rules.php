<?php

return array(
        /*USER INFORMATION*/
        'user_id'               =>      'integer|exists:users,id',
        'client_id'             =>      'integer|exists:clients,id',

        'firstname'             =>      'min:2',
        'lastname'              =>      'min:2',
        'fullname'              =>      'min:3',
        'username'             	=>      'min:6',
        'email'                 =>      'email',
        'password'              =>      'min:6',
        'password_confirmation' =>      'min:6',
        'zipcode'               =>      'digits_between:5,10',
        'check'                 =>      'accepted',
        'state'                 =>      'min:2',
        'city'                  =>      'min:3',
        'address'               =>      'min:4',
        'country'               =>      'min:2',
        'phone'                 =>      'min:3',
        'phone_num'             =>      'numeric|min:3',
        'webpage'               =>      'active_url',
        'webpage_only'          =>      'url',
        'twitter'               =>      'alpha_num|min:1',
        'avatar'                =>      'image|min:1|max:3000|mimes:png,jpg,jpeg',
        'yvideo'                =>      'min:3|max:100',
    	'client_type'           =>      'integer|exists:client_type,id',
		

        /*GENERAL INFORMATION*/
        'array'                 =>      'array',
        'ip_list'               =>      'alpha_num|min:7',
        'amount'                =>      'numeric' //'regex:/^[0-9]{1,3}(,?[0-9]{3})*\.[0-9]+$/'
        ,
        'period'                =>      'in:year,month,day,hour,minute,second',
        'comment'               =>      'min:4',
        'date'                  =>      'date_format:Y-m-d',
        'searchterm'            =>      'min:2',
        'gentext'               =>      'min:2',
        'gentextmin'            =>      'min:1',
        'year'                  =>      'numeric',
        'month'                 =>      'numeric',
        'ccv'                 	=>      'numeric',
        'number'                =>      'numeric',
        'creditcard'     		=>      'spacesnum',
);
