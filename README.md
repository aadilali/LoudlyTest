# Invitation System API Documentation

This documentation provides details about the endpoints and their parameters for the Invitation System API.

## Endpoints

### Get Invitation
- **URL**: `/api/invitation`
- **Method**: `GET`
- **Description**: Retrieves information about an invitation.

### Send Invitation
- **URL**: `/api/invitation`
- **Method**: `POST`
- **Description**: Sends a new invitation.

**Parameters**:
- `sender_id` (required): The ID of the sender user.
- `invited_id` (required): The ID of the invited user.
- `description` (required): A description for the invitation.

### Cancel Invitation
- **URL**: `/api/invitation`
- **Method**: `PUT`
- **Description**: Cancels an existing invitation.

**Parameters**:
- `invitation_id` (required): The ID of the invitation to cancel.

### Respond to Invitation
- **URL**: `/api/accept_invitation`
- **Method**: `PUT`
- **Description**: Respond to an invitation (accept or decline).

**Parameters**:
- `invitation_id` (required): The ID of the invitation to respond to.
- `response` (required): The response to the invitation (either "accept" or "decline").

## Example Usage

### Send Invitation
To send an invitation, make a POST request to `/api/invitation` with the following JSON data:

```json
{
  "sender_id": 1,
  "invited_id": 2,
  "description": "Let's connect!"
}
