# ONonce #
**Version:** 1.0.0   


A configurable PHP pseudo nonce class.  It does not generate "true" nonces as (for simplicity's sake) they are not guaranteed to be unique forever.  This allows for a straightforward class definition without any database interaction.

## Description ##

When I went looking for an existing PHP nonce implementation, those that I found had parts of what I needed but not quite precisely what I was after.  Consequently, this was developed with a focus on simplicity and easy configuration.  

You can set your secret and go OR you can change any or all of the following:

* hash algorithm
* nonce length
* nonce offset
* nonce form input name
* nonce query parameter name
* [secret]
* default current user ID
* default lifetime

This was developed with inspiration from various sources, including:

* [WordPress Nonces](https://codex.wordpress.org/WordPress_Nonces)
* [Full Throttle's PHP NONCE Library](http://fullthrottledevelopment.com/php-nonce-library)

Further, [this Stack Overflow thread](http://stackoverflow.com/questions/4145531/how-to-create-and-use-nonces/4145848#4145848) has a good discussion on the topic

No attribution is necessary to use ONonce; I am merely hopeful it will prove useful to you in your projects.


## Installation ##

1. Place <code>ONonce.php</code> in your project's <code>includes</code> (or equivalent) directory
2. Review the defaults and, at a minimum, update the SECRET to your own, unique string
3. Add it to the files from which you need to call it:

    include 'ONonce.php';

## Usage ##

### Hidden form input ###

1. Create (with defaults):

    echo ONonce::create_form_input('name_value', 'action_value');  

2. Confirm (with defaults):

    $nonce_from_form = sanitize($_POST['_ononce']);
    if (ONonce::is_valid('name_value', 'action_value', $nonce_from_form))
    {
  
        // Do something  
  
    }  
    
3. Create (with overrides):

    echo ONonce::create_form_input('name_value', 'action_value', array('current_user' => 12, 'lifetime' => 300));

4. Confirm (with overrides):

    $nonce_from_form = sanitize($_POST['_ononce']);
    if (ONonce::is_valid('name_value', 'action_value', $nonce_from_form, array('current_user' => 12, 'lifetime' => 300)))
    {
    
        // Do something
        	
    }

### URL fragment ###

1. Create (with defaults):

    echo 'https://some_url.com?query_param1=foo/&'.ONonce::create_url_fragment('name_value', 'action_value');

2. Confirm (with defaults):

    $nonce_from_url = sanitize($_GET['ononce']);
    if (ONonce::is_valid('name_value', 'action_value', $nonce_from_url))
    { 
  
        // Do something  
  
    }
    
3. Create (with overrides):

    echo 'https://some_url.com?query_param1=foo/&'.ONonce::create_url_fragment('name_value', 'action_value', array('current_user' => 12, 'lifetime' => 300));
    
4. Confirm (with overrides):

    $nonce_from_url = sanitize($_GET['ononce']);
    if (ONonce::is_valid('name_value', 'action_value', $nonce_from_url, array('current_user' => 12, 'lifetime' => 300)))
    {
  
        // Do something  
  
    }
    
## Changelog ##

### 1.0.0 ###
Release Date: February 7, 2016

* Initial release
