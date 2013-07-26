# Elgg WeatherBlur REST API #
---
*JSONP not supported

### Login/Get Investigations ###
Method: GET<br>
URL: <br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_invs&username=blah&password=blah`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.get_invs&username=blah&password=blah`

**get_invs**

    parameters
    	method : wb.get_inv,
        username : blah,
        password : blah

    returns
        {
            status : <int>,
            results : {
                user_guid : <int>,
                username: <string>,
                token : <string>,
                invs : [
                   {
                        name : <string>,
                        guid : <int>
                   },
                   {
                        name : <string>,
                        guid : <int>
                   }
                ]
            }
        }

### Create Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.create_obs&inv_guid=42&token=b8a0d67b16669580eaabf979454b93ae&agg_id=10`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.create_obs&inv_guid=42&token=b8a0d67b16669580eaabf979454b93ae&agg_id=10`

**create_obs**

    parameters
    	method : wb.create_obs,
        inv_guid : 42,
        agg_id : 10,
        token : b8a0d67b16669580eaabf979454b93ae

    returns
        {
            status : <int>,
            results : <obs_id>
        }

### Get Observation by User Type ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_obs_by_user_type&user_type=Student&min_date=1374690933&max_date=1374691000`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.get_obs_by_user_type&user_type=Student&min_date=1374690933&max_date=1374691000`

**get_obs_by_user_type**

    parameter
    	method : wb.get_obs_by_user_type,
        user_type : Student,
        min_date : <epoch>
        max_data : <epoch>

    valid user_types are:
        "Student", "Teacher", "Scientist", "Fisherman", "Community Member", 

    returns 
        {
            "status" : <int>,
            "results" : [
            	{
            		"agg_ids" : <int>, 
                	"user_display_name" : <string>,
                	"username" : <string>,
                	"user_id" : <int>
            	},
            	{
            		"agg_ids" : <int>, 
                	"user_display_name" : <string>,
                	"username" : <string>,
                	"user_id" : <int>	
            	}
            ]
        }

### Get Observation by Investigation (work in progress)###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_obs_by_inv&inv_id=43`

Full URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.get_obs_by_inv&inv_id=43`

**get_obs_by_user_type**

    parameter
    	method : wb.get_obs_by_inv,
        inv_id : 43

    returns 
        {
        	"status" : <int>
        	"results" : [
				{
					"time_created" : <int>,
					"agg_id": <int>,
					"time_created": <epoch>
				},
				{
					"time_created" : <int>,
					"agg_id": <int>,
					"time_created": <epoch>
				}
			]
        }

### Toggle Like on Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.toggle_like_obs&observation_guid=50&token=614ad70385f0fbda481adb4b32c1bf3a`

Full URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.toggle_like_obs&observation_guid=50&token=614ad70385f0fbda481adb4b32c1bf3a`

**toggle_like_obs**

    parameter
        method : wb.toggle_like_obs,
        observation_guid : 50,
        token : 614ad70385f0fbda481adb4b32c1bf3a 

	returns
		{
    		"status": <int>,
    		"result": {
        		"my_like": <0 or 1>,
        		"all_likes": <int>
    		}
		}

### Get Likes on Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_likes&observation_guid=52&token=f286d4fbc37593d2d7a18d96a56fa066`

Full URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.get_likes&observation_guid=52&token=f286d4fbc37593d2d7a18d96a56fa066`

**get_likes**

	parameter
		method : wb.get_likes,
		observation_guid : 50,
		token : f286d4fbc37593d2d7a18d96a56fa066
	
	returns
		{
    		"status": 0,
    		"result": {
        		"my_like": <0 or 1>,
        		"all_likes": <int>
    		}
		}

### Comment on Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.comment_on_obs&observation_guid=50&token=614ad70385f0fbda481adb4b32c1bf3a&comment=LOL`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.comment_on_obs&observation_guid=50&token=614ad70385f0fbda481adb4b32c1bf3a&comment=LOL`

**comment_on_obs**

	parameter
		method : wb.comment_on_obs,
		observation_guid : 50,
		token : 614ad70385f0fbda481adb4b32c1bf3a
	
	returns
		{
			{
    			"status": <int>,
    			"result": <0 or 1>
			}
		}

### Get Comments on Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_comments_on_obs&observation_guid=50`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.get_comments_on_obs&observation_guid=50`

**get_comments_on_obs**

	parameter
		method : wb.get_comments_on_obs,
		observation_guid : 50
		
	returns
		{
		   "status": 0,
    		"result": [
        		{
            		"time_created": <int>,
            		"value": <string>
        		}
    		]
		}

### Is Logged In ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.is_logged_in`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.is_logged_in`

**is_logged_in**

	parameter
		method : wb.is_loggend_in
	
	returns (result is the user's token)
		{
    		"status": 0,
    		"result": "614ad70385f0fbda481adb4b32c1bf3a"
		}

### get_user_info ###
Method: GET<br>
URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_user_info&user_id=49`

FULL URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.get_user_info&user_id=49`

**get_user_info**

	parameter
		method : wb.get_user_info,
		user_id : 49
		
	returns (image url append tiny, topbar, small, medium, large, or master to the url for image size)
		{
			"status": 0,
    		"result": {
        		"users_display_name": "name",
        		"username": "john",
        		"image": "http://localhost:9999/elgg/mod/profile/icondirect.php?lastcache=1374841270&joindate=1374680200&guid=49&size=",
        		"email": "john.longanecker@nbtsolutions.com",
        		"profile_type": "Student"
    		}
		}
		
### get_user_info_by_agg_id ###
Method: GET<br>
URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_user_info_by_agg_id&agg_id=11`

FULL URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.get_user_info_by_agg_id&agg_id=11`

**get_user_info_by_agg_id**

	parameter
		method : wb.get_user_info_by_agg_id,
		agg_id : 11
		
	returns (image url append tiny, topbar, small, medium, large, or master to the url for image size)
		{
			"status": 0,
    		"result": {
        		"users_display_name": "name",
        		"username": "john",
        		"image": "http://localhost:9999/elgg/mod/profile/icondirect.php?lastcache=1374841270&joindate=1374680200&guid=49&size=",
        		"email": "john.longanecker@nbtsolutions.com",
        		"profile_type": "Student"
    		}
		}