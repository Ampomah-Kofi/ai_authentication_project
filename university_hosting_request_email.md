# Draft email: university hosting request

**To:** Kaylee
**CC:** Dr. Le Nhat Tu
**Subject:** University hosting options for IRB-reviewed authentication study

Hi Kaylee,

I am preparing an IRB protocol for a web-based usable security study supervised by Dr. Le Nhat Tu. The study will recruit adult U.S. university students through Prolific. Participants will complete a short interactive authentication and AI-permission task and then continue to a Qualtrics survey.

We would like to use university-managed infrastructure for both the web application and behavioral-event storage. The application needs:

- HTTPS hosting for a static front end;
- two POST-only API routes for completed/abandoned task records and withdrawal requests;
- a managed relational database for JSON event payloads;
- no public read, update, or delete access;
- researcher access restricted to the approved study team;
- documented backup, retention, deletion, logging, and data-location controls suitable for the IRB application; and
- the ability to receive moderate Prolific study traffic without collecting real credentials or account data.

Could you let us know which university Azure or departmental services would be appropriate, how we should request access, and whether there is a technical or data-governance contact we should consult? We would also appreciate any standard language about the service that can be included in the IRB protocol.

Thank you,

Kofi Ampomah
