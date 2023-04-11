<?php

namespace App\Http\Middleware; // Define the namespace for the middleware

use Auth; // Import the Auth class from the Illuminate\Support\Facades namespace
use Closure; // Import the Closure class from the global namespace
use Illuminate\Session\Store; // Import the Store class from the Illuminate\Session namespace

class CheckUserActivity // Define a new PHP class called CheckUserActivity
{
    protected $session; // Define a protected property called $session on the CheckUserActivity class

    public function __construct(Store $session) // Define a public constructor method that accepts an instance of the Store class
    {
        $this->session = $session; // Assign the $session parameter to the $session property on the object
    }

    public function handle($request, Closure $next) // Define a public handle method that accepts $request and $next parameters
    {
        $userLastActivity = $this->session->get('last_activity'); // Get the 'last_activity' value from the user's session data and assign it to the $userLastActivity variable

        if ($userLastActivity && (time() - $userLastActivity > 3600)) { // If $userLastActivity is truthy (i.e. it exists and is not null) and the current time minus $userLastActivity is greater than 1800 seconds (i.e. 30 minutes)
            Auth::logout(); // Log the user out
            $this->session->flush(); // Clear the user's session data
            return redirect('/')->with('message', 'You have been logged out due to inactivity.'); // Redirect the user to the login page with a message indicating that they have been logged out due to inactivity
        }

        $this->session->put('last_activity', time()); // Set the 'last_activity' value in the user's session data to the current time

        return $next($request); // Call the next middleware or controller method in the request chain
    }
}