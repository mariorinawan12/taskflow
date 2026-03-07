<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TaskFlow Invitation</title>
</head>

<body style="font-family: sans-serif; background: #f9f9f9; padding: 40px">
    <div
        style="max-width: 400px; margin: 0 auto; background: white; border-radius: 12px; padding:32px; border:1px solid #eee ">
        <h2 style="color: #111; margin: bottom 8px;">
            You are invited to {{ $invitation->workspace->name }};
        </h2>

        <p style="color: #555; margin-bottom: 24px">
            {{ $invitation->inviter->name }} invited you to join as <strong>{{ $invitation->role->value }}</strong>
            in workspace <strong>{{ $invitation->workspace->name }}</strong>
        </p>

        <a href="{{ url('/invitations/' . $invitation->token) }}" style="display: inline-block; padding: 12px 24px;
                  background: #84cc16; color: black;
                  border-radius: 8px; text-decoration: none;
                  font-weight: bold;">
            Accept Invitation
        </a>


        <p style="color: #999; font-size: 12px; margin-top: 24px;">
            This link expired in 7 days.
            If you don't feel like receiving it, ignore it.
        </p>
    </div>
</body>

</html>