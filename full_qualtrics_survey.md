# Full Qualtrics Survey Draft

Study title: AI Authentication and Permission Review Study

Use after the HTML behavioral task. Qualtrics should capture the participant code from the URL parameter:

`pid = ${e://Field/pid}`

## Research Questions

**RQ1.** What mental models do university students hold of single sign-on (SSO), multi-factor authentication (MFA), and AI agent authorization, and across these three layers, where do misconceptions cluster?

**RQ2.** How carefully do students review a consent screen before granting access, and do they notice when the access requested is broader than the task needs?

**RQ3.** Does attention to a consent screen drop when it comes at the end of a chain of login and approval steps, compared with seeing it fresh?

**RQ4.** Do students know how to manage delegated access: can they revoke access they have granted, find a record of what an AI agent has done, and tell their own actions apart from actions an agent took for them?

---

## Block 1. Entry and Eligibility

### 1. Participant Code

Your participant code is captured automatically from the previous task. If the field below is empty, please enter the code shown on the task completion screen.

Question type: text entry. Display logic: hidden if `${e://Field/pid}` is populated; shown only if blank.

### 2. Age Confirmation

I am 18 years of age or older.

- Yes
- No

Routing: if No, end the survey.

### 3. Student Status

I am currently enrolled at the University of Alabama.

- Yes
- No

Routing: if No, set `ineligible = true` and continue.

---

## Block 2. Immediate Post-Task Reflection

### 4. Realism

How realistic did the simulated sign-in and permission task feel?

- (1) Not realistic at all
- (2) Slightly realistic
- (3) Moderately realistic
- (4) Very realistic
- (5) Extremely realistic

### 5. Self-Reported Attention

During the task, how carefully did you read the permission screen before making your decision?

- (1) I did not read it
- (2) I skimmed it quickly
- (3) I read some of it
- (4) I read most of it
- (5) I read all of it carefully

### 6. Self-Reported Scrolling

Did you scroll through the full permission list during the task?

- Yes
- No
- I do not remember

### 7. Decision and Broad-Access Reflection

Briefly explain why you chose to allow or cancel access during the task, and whether anything about what the assistant asked for stood out to you.

Question type: open text.

### 8. Suspicion Check

Before the debrief, what did you think the study was mainly examining?

Question type: open text.

---

## Block 3. Familiarity and Exposure

### 9. SSO Frequency

In the past month, how often have you logged in to a site or app using an existing account, such as "Continue with Google," "Continue with Microsoft," or "Continue with Apple"?

- (1) Never
- (2) Rarely
- (3) Monthly
- (4) Weekly
- (5) Daily

### 10. Verification Frequency

In the past month, how often have you had to verify your identity using a second method, such as a code, phone prompt, or fingerprint?

- (1) Never
- (2) Rarely
- (3) Monthly
- (4) Weekly
- (5) Daily

### 11. AI Assistant Use

Have you used an AI assistant that can act on your behalf, such as reading files, drafting or sending messages, scheduling events, or connecting to another service?

- Yes, regularly
- Yes, once or twice
- No
- Not sure

### 12. Technology Comfort

How comfortable are you with technology in general?

- (1) Not at all comfortable
- (2) Slightly comfortable
- (3) Moderately comfortable
- (4) Very comfortable
- (5) Extremely comfortable

---

## Block 4. Security Behavior Baseline

The following items use the same scale: Never (1), Rarely (2), Sometimes (3), Often (4), Always (5).

### 13.

When someone sends me a link, I open it only after checking where it actually goes.

- (1) Never
- (2) Rarely
- (3) Sometimes
- (4) Often
- (5) Always

### 14.

I read what a website or app is asking for before I agree to it.

- (1) Never
- (2) Rarely
- (3) Sometimes
- (4) Often
- (5) Always

### 15.

I notice when a site or app is behaving in a way that seems off, and I stop before continuing.

- (1) Never
- (2) Rarely
- (3) Sometimes
- (4) Often
- (5) Always

### 16. Attention Check 1

To show you are reading carefully, please select "Often" for this item.

- (1) Never
- (2) Rarely
- (3) Sometimes
- (4) Often
- (5) Always

Scoring: pass if Often, fail otherwise.

---

## Block 5. Mental Models: Three Delegation Layers

### 17. SSO Default Access

When you sign in to an app using an existing account, such as Google, Microsoft, or Apple, what do you assume the app receives by default?

- Nothing; it only confirms I am a real person
- Confirmation of my identity plus limited profile details the app asks for, such as name and email
- Full access to that account, including messages and files
- Not sure

Keyed answer: identity confirmation plus limited profile details.

### 18. SSO and Email Scope

"Signing in to an app with my Google or Microsoft account automatically lets that app read my email."

- True
- False
- Not sure

Keyed answer: False.

### 19. SSO Open-Text Understanding

In one or two sentences, what do you think happens when you sign in to an app using an existing account like Google, Microsoft, or Apple?

Question type: open text. Coded post hoc as accurate, partially accurate, inaccurate, or off-topic, with Cohen's kappa reported.

### 20. MFA Purpose

What is the main thing two-step verification, such as a code or phone approval after your password, is meant to protect against?

- Weak passwords
- Someone using a password that was stolen or guessed
- Computer viruses
- A slow or unreliable internet connection
- Not sure

Keyed answer: someone using a password that was stolen or guessed.

### 21. SSO Replaces MFA Misconception

"If I use single sign-on, I do not really need two-step verification, because the sign-on already covers it."

- True
- False
- Not sure

Keyed answer: False.

### 22. AI Access Persistence

When you allow an AI assistant to connect to one of your accounts, such as email or calendar, what do you assume it can do afterward?

- Only the single action I asked for, that one time
- Whatever the granted access allows, until I remove that access
- Only things it does while I am watching
- Not sure

Keyed answer: whatever the granted access allows, until I remove that access.

### 23. AI Agent Autonomy

"An AI assistant only does something at the exact moment I ask; it cannot act on its own later."

- True
- False
- Not sure

Keyed answer: False.

### 24. Permission Clarity Importance

How important is it to you that an AI assistant clearly explains what account permissions it is requesting?

- (1) Not important at all
- (2) Slightly important
- (3) Moderately important
- (4) Very important
- (5) Extremely important

---

## Block 6. Attention Across Authentication Steps

### 25. Attention Across Steps

When you go through several login or approval steps in a row, such as sign-in, provider handoff, and MFA approval, how does your attention to each new screen usually change?

- I read each one just as carefully
- I pay a little less attention to later ones
- I pay a lot less attention to later ones
- I usually approve or continue to get through them
- Not sure

### 26. Login Fatigue

How mentally tiring do repeated login and approval steps feel to you?

- (1) Not tiring at all
- (2) Slightly tiring
- (3) Moderately tiring
- (4) Very tiring
- (5) Extremely tiring

---

## Block 7. Revocation and Auditability

### 27. Knowing Where to Revoke

If you wanted to stop an app or AI assistant from being able to access your account, would you know where to do that?

- Yes, confidently
- I think so
- No
- I have never thought about it

### 28. Revocation Location

Briefly, where would you go to remove that access?

Question type: open text. Coded as correct, partially correct, or incorrect.

### 29. Prior Revocation Experience

Have you ever reviewed or removed an app's or service's access to one of your accounts?

- Yes
- No
- I am not sure what that means

### 30. AI Action Attribution

If an AI assistant sent an email for you, do you think you could find a record showing that the assistant sent it rather than you?

- Yes
- No
- Not sure

### 31. Attention Check 2

To show you are paying attention, please select "False" for this item:

"Most people sleep eight hours a night."

- True
- False
- Not sure

Scoring: pass if False, fail otherwise.

---

## Block 8. Demographics and Closing

### 32. Major or Field of Study

What is your major or field of study?

Question type: open text. Coded post hoc as technical, non-technical, or mixed.

### 33. Degree Level

What is your current degree level?

- Undergraduate
- Master's
- Doctoral or PhD
- Other
- Prefer not to say

### 34. Gender

Optional.

- Woman
- Man
- Non-binary
- Prefer to self-describe
- Prefer not to say

### 35. Prior Cybersecurity Coursework

Have you taken a course or formal training related to cybersecurity, privacy, or computer security?

- Yes
- No
- Not sure

### 36. Final Comment

Is there anything else you would like to tell us about the task, account permissions, or AI assistants?

Question type: open text. Optional.

---

## Closing Screen

Thank you for completing the survey. Your responses have been recorded. If you have any questions about the study, please contact Kofi Ampomah at kampomah@crimson.ua.edu or Kunal Sarna at ksarna@crimson.ua.edu. For questions about your rights as a research participant, contact the University of Alabama Office for Research Compliance at 1-877-820-3066 or rscompliance@ua.edu.

This study is conducted by the UA SPECTRAL Lab at the University of Alabama.

---

## Qualtrics Build Notes

### Text Entry - Single Line

- Q1 (participant code)
- Q32 (major or field of study)

### Text Entry - Essay Box

- Q7 (decision and broad-access reflection)
- Q8 (suspicion check)
- Q19 (SSO open-text understanding)
- Q28 (revocation location)
- Q36 (final comment, optional)

### Multiple Choice - Single Answer

Use radio buttons with a vertical layout for every other item in the survey:

- Q2 (age confirmation)
- Q3 (student status)
- Q4 (realism)
- Q5 (self-reported attention)
- Q6 (self-reported scrolling)
- Q9 (SSO frequency)
- Q10 (verification frequency)
- Q11 (AI assistant use)
- Q12 (technology comfort)
- Q17 (SSO default access)
- Q18 (SSO and email scope)
- Q20 (MFA purpose)
- Q21 (SSO replaces MFA misconception)
- Q22 (AI access persistence)
- Q23 (AI agent autonomy)
- Q24 (permission clarity importance)
- Q25 (attention across steps)
- Q26 (login fatigue)
- Q27 (knowing where to revoke)
- Q29 (prior revocation experience)
- Q30 (AI action attribution)
- Q31 (attention check 2)
- Q33 (degree level)
- Q34 (gender)
- Q35 (prior cybersecurity coursework)

### Matrix Table - Single Answer per Row

Combine Q13, Q14, Q15, and Q16 onto one Qualtrics screen as a single Matrix Table question with four statement rows, sharing the Never / Rarely / Sometimes / Often / Always scale. This counts as four items for analysis but renders as one matrix question for the participant.

- Q13 (link checking)
- Q14 (read what apps ask for)
- Q15 (notice when something is off)
- Q16 (attention check 1 - embedded in the matrix)
