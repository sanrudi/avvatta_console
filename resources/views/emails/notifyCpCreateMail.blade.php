@component('mail::message')
# Dear {{$data['name']}},

Your administrator has created a user account for you.<br>
Domain name: <a href="{{ url('login') }}">{{ url('login') }}</a><br>
User Email: {{$data['email']}}<br>
User Password: {{$data['password']}}<br>
Click here to log in to <a href="{{ url('login') }}">avvatta console</a>. (You can add this link to your Favorites.)<br>
If you have any questions, contact your administrator admin@avvatta.com.<br>

Thanks<br>
@endcomponent
