@component('mail::message')
#<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
		<tr>
			<td align="center" style="padding:0;">
				<table role="presentation" style="width:800px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
					<tr>
						<td align="center" style="padding:40px 0 30px 0;background:#000000;">
							<img src="https://avvatta.com:8100/console/images/avvatta-logo.PNG" alt="" width="400" style="height:auto;display:block;" />
						</td>
					</tr>
					<tr>
						<td style="padding:36px 30px 42px 30px;">
							<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
								<tr>
									<td style="padding:0 0 36px 0;color:#153643;">
										<h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Dear {{$data['name']}};</h1>
										<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Here are your Credentials to access the Avvatta Console portal</p>
                    
                    <p>Email Address:{{$data['email']}}</p>
                    <p> Password:{{$data['password']}}</p>
<p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Click here to redirect to login <a  href="{{ url('login') }}" style="color:#ee4c50;text-decoration:underline;">Avvatta Console </a></p>
									</td>
								</tr>
								<tr>
									<td style="padding:0;">
										<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
											
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding:30px;background:#000000;">
							<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
								<tr>
									<td style="padding:0;width:50%;" align="left">
										<p style="margin:0;font-size:14px;line-height:22px;font-family:Arial,sans-serif;color:#ffffff;">
											&reg;  Digital Media 333 Pty Ltd, 2022<br/>
										</p>
									</td>
									<td style="padding:0;width:50%;" align="right">
										<table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
											<tr>
												<td style="padding:0 0 0 10px;width:38px;">
													<a href="https://twitter.com/Avvatta1" style="color:#ffffff;"><img src="https://avvatta.com/assets/icons/t.png" alt="Twitter" width="38" style="height:auto;display:block;border:0;" /></a>
												</td>
												<td style="padding:0 0 0 10px;width:38px;">
													<a href="https://www.facebook.com/Avvatta-109816714518275/" style="color:#ffffff;"><img src="https://avvatta.com/assets/icons/face.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
												</td>
                        <td style="padding:0 0 0 10px;width:38px;">
													<a href="https://www.youtube.com/channel/UCfQj2tq3YEE447z5xQ7Dyzw?view_as=subscriber" style="color:#ffffff;"><img src="https://avvatta.com/assets/icons/y.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
												</td>
                        <td style="padding:0 0 0 10px;width:38px;">
													<a href="https://www.instagram.com/avvatta.za/" style="color:#ffffff;"><img src="https://avvatta.com/assets/icons/i.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
												</td>
											</tr>
                     
										</table>
                    
									</td>
								</tr>
                 <tr>
                   <td>
                     <table>
                        <tbody>
                          <tr>
                            
                             <td style="width: 100px; margin:0;font-size:14px;line-height:14px;font-family:Arial,sans-serif;color:#ffffff;"><a href="https://avvatta.com/#/about" style="
    color: white;
    text-decoration: none;
">About Us</a></td>
                        <td style="width: 80px; margin:0;font-size:14px;line-height:14px;font-family:Arial,sans-serif;color:#ffffff;"><a href="https://avvatta.com/#/help/faq" style="
    color: white;
    text-decoration: none;
">FAQ</a></td>
                        <td style="width: 300px;margin:0;font-size:14px;line-height:14px;font-family:Arial,sans-serif;color:#ffffff;"><a href="https://avvatta.com/#/help/terms"style="
    color: white;
    text-decoration: none;
" >Terms & Conditions</a></td>
                        <td style="width: 100px; margin:0;font-size:14px;line-height:14px;font-family:Arial,sans-serif;color:#ffffff;"><a href="https://avvatta.com/#/help/privacy"style="
    color: white;
    text-decoration: none;
">Privacy and Policy</a></td>
                    <td style="width: 100px; margin:0;font-size:14px;line-height:14px;font-family:Arial,sans-serif;color:#ffffff;"><a href="https://avvatta.com/#/help/contact" style="
    color: white;
    text-decoration: none;
">
                      Contact Us
                    </a></td>
                          </tr>
                       </tbody>
                     </table>
                   </td>
                       
                      </tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

@endcomponent
