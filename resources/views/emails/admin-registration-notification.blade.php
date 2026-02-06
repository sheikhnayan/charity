@extends('emails.layout')

@section('content')
<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    
    <!-- Header Section -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">New Registration Pending</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0;">{{ $website->name }}</p>
    </div>

    <!-- Main Content -->
    <div style="background: #f9f9f9; padding: 40px; border-radius: 0 0 8px 8px;">
        
        <!-- Greeting -->
        <p style="color: #666; font-size: 16px; margin-bottom: 20px;">
            Hello Admin,
        </p>

        <!-- Alert Box -->
        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; color: #856404;">
                <strong>A new {{ $userRole }} has registered and requires approval.</strong>
            </p>
        </div>

        <!-- Registration Details -->
        <div style="background: white; padding: 20px; border-radius: 4px; margin: 20px 0; border: 1px solid #e0e0e0;">
            <h3 style="color: #333; font-size: 16px; margin-top: 0; border-bottom: 2px solid #667eea; padding-bottom: 10px;">Registration Details</h3>
            
            <table style="width: 100%; margin-top: 15px;">
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 12px 0; width: 30%; font-weight: bold; color: #667eea;">Name:</td>
                    <td style="padding: 12px 0; color: #333;">{{ $newUser->name }} {{ $newUser->last_name }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 12px 0; width: 30%; font-weight: bold; color: #667eea;">Email:</td>
                    <td style="padding: 12px 0; color: #333;">{{ $newUser->email }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 12px 0; width: 30%; font-weight: bold; color: #667eea;">Account Type:</td>
                    <td style="padding: 12px 0; color: #333;">{{ $userRole }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; width: 30%; font-weight: bold; color: #667eea;">Registered:</td>
                    <td style="padding: 12px 0; color: #333;">{{ $registrationDate }}</td>
                </tr>
            </table>
        </div>

        <!-- Action Button -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $approvalLink }}" style="display: inline-block; background: #667eea; color: white; text-decoration: none; padding: 12px 30px; border-radius: 4px; font-weight: bold; font-size: 14px;">
                Review & Approve Registrations
            </a>
        </div>

        <!-- Status Info -->
        <div style="background: #f0f7ff; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #0099ff;">
            <p style="margin: 0; color: #0066cc; font-size: 14px;">
                <strong>Note:</strong> This account will remain inactive until you approve it. Once approved, the user will receive an email confirming their access.
            </p>
        </div>

        <!-- Footer Message -->
        <p style="color: #666; font-size: 13px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
            This is an automated notification from {{ $website->name }}. If you have any questions, please contact your system administrator.
        </p>

    </div>

</div>
@endsection
