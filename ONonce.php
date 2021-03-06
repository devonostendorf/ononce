<?php
/**
 * A configurable PHP pseudo nonce class.
 *
 * @since		1.0.0
 * @package		ONonce
 * @author		Devon Ostendorf <devon@devonostendorf.com>
 * @link		https://devonostendorf.com/projects/#ononce
 */
class ONonce {
	
	// Hash algorithm to use
	const HASH_ALGORITHM = 'sha512';
	
	// Length of nonce
	const NONCE_LENGTH = 12;
	
	// Position within hash to start nonce generation
	const NONCE_OFFSET = -15;	// 15 chars from end of string
	
	// Name of form input to store nonce
	const NONCE_FORM_INPUT_NAME = '_ononce';
	
	// Name of query parameter to pass nonce
	const NONCE_QUERY_PARAM_NAME = 'ononce';
	
	// Secret: Make this a looong (64? character) string of randomness
	const SECRET = 'R*Q3Q*t|?,;c;RR2g?<:PIB0J+T/7kDkSS-VQeH^N3-q:XbdCgS>G+!*l<}|)S.h';
	
	// Default current user ID
	const DEFAULT_CURRENT_USER_ID = 123456789;
	
	// Default lifetime for nonces (in seconds)
	const DEFAULT_LIFETIME = 3600;	// 1 hour

 	/**
	 * Create nonce as a string of NONCE_LENGTH, valid for DEFAULT_LIFETIME
	 * seconds (unless overridden).
	 *
	 * @since	1.0.0
	 * @param	string		$name			Nonce name
	 * @param	string		$action			Nonce action
	 * @param	string[]	$overrides		Optional. Array containing 'current_user' and/or 'lifetime' override values.
	 * @uses	self::DEFAULT_CURRENT_USER_ID
	 * @uses	self::DEFAULT_LIFETIME
	 * @uses	self::HASH_ALGORITHM
	 * @uses	self::SECRET
	 * @uses	self::NONCE_OFFSET
	 * @uses	self::NONCE_LENGTH
	 * @return	string
	 */
    private static function create($name, $action, array $overrides = NULL)
    {		  
    	
    	if ( ! isset($overrides['current_user']))
    	{
    		
    		// No current user ID was passed - use default
    		$overrides['current_user'] = self::DEFAULT_CURRENT_USER_ID;
    	}
    	if ( ! isset($overrides['lifetime']))
    	{

    		// No lifetime was passed - use default
    		$overrides['lifetime'] = self::DEFAULT_LIFETIME;
    	}

        $deadline = ceil(time() / ($overrides['lifetime'] / 2));

        return substr(hash(self::HASH_ALGORITHM, $deadline.$name.$action.$overrides['current_user'].self::SECRET), self::NONCE_OFFSET, self::NONCE_LENGTH);
                
    }

 	/**
	 * Create a string containing HTML markup, for a hidden form input field,
	 * with a name of NONCE_FORM_INPUT_NAME and a value consisting of the nonce
	 * generated by self::create().
	 *
	 * @since	1.0.0
	 * @param	string		$name			Nonce name
	 * @param	string		$action			Nonce action
	 * @param	string[]	$overrides		Optional. Array containing 'current_user' and/or 'lifetime' override values.
	 * @uses	self::NONCE_FORM_INPUT_NAME
	 * @uses	self::create
	 * @return	string
	 */
    public static function create_form_input($name, $action, array $overrides = NULL)
    {               

        return '<input type="hidden" name="'.self::NONCE_FORM_INPUT_NAME.'" value="'.self::create($name, $action, $overrides).'" />';
 
    }

 	/**
	 * Create a string containing a URL fragment with a name of 
	 * NONCE_QUERY_PARAM_NAME (and, if NONCE_QUERY_PARAM_NAME is not blank, a
	 * literal "="), and a value consisting of the nonce generated by
	 * self::create().
	 *
	 * @since	1.0.0
	 * @param	string		$name			Nonce name
	 * @param	string		$action			Nonce action
	 * @param	string[]	$overrides		Optional. Array containing 'current_user' and/or 'lifetime' override values.
	 * @uses	self::NONCE_QUERY_PARAM_NAME
	 * @uses	self::create
	 * @return	string
	 */
    public static function create_url_fragment($name, $action, array $overrides = NULL)
    {               
                            
        // NOTE: Some routing schemes may not use query string parameters, instead opting to use only delimited
        //	values
        if (($url_fragment = self::NONCE_QUERY_PARAM_NAME) != '')
        {
        	$url_fragment .= '=';
        }
        $url_fragment .= self::create($name, $action, $overrides);
       
        return $url_fragment;

    }

 	/**
	 * Determine whether a supplied nonce is valid and return success/failure.
	 *
	 * @since	1.0.0
	 * @param	string		$name			Nonce name
	 * @param	string		$action			Nonce action
	 * @param	string		$nonce			Nonce value
	 * @param	string[]	$overrides		Optional. Array containing 'current_user' and/or 'lifetime' override values.
	 * @uses	self::create
	 * @return	boolean
	 */
    public static function is_valid($name, $action, $nonce, array $overrides = NULL)
    {               
                    
        return self::create($name, $action, $overrides) == $nonce;

    }

} // End ONonce
