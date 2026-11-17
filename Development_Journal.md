# The Last Ember — Development Journal

Jeremy Henry-Atohengbe  
CSC 6370 · Project 2  
Solo project

### Page structure and navigation

I set up the PHP pages and linked them before wiring up registration or gameplay.
That gave me a way to check the flow from sign-in to character creation and preview
the opening scene while the forms were still being built.

### Registration, sign-in, and sign-out

I stored password hashes in a private directory outside the public game folder
and used PHP sessions to track who was signed in. Registration hit a permissions
error on Codd. I checked whether PHP could access the directory, followed Codd
support's permission instructions, and confirmed registration worked afterward.
I also tested sign-out and checked that protected pages required a new sign-in.

### Character classes and story branches

I built three character classes and organized the story into 15 scenes. A choice
can lead to a new scene, and some choices have requirements the hero must meet.
I checked the gates and played through all three endings to verify that the
player's choices affected where the story ended.

### Advisor, alignment, and scoring

I added advisor previews to give players a clue before they commit to a choice.
For alignment, I used a score of 3 or higher for Protective and -3 or lower for
Ruthless. Scores in between show Uncertain. On Codd, I verified the ending
scores of 50 for Secret, 45 for Heroic, and 25 for Tragic.

### Leaderboard and checkpoint

I made the leaderboard record each completed run once. It ranks by score and
uses the order of completion to break ties. I checked that refreshing the
ending didn't add a duplicate. For save and resume, I used a cookie token to
identify the checkpoint and tied the saved run to the signed-in account.

### Styling and final Codd checks

I applied shared styling across the game and checked the story page at a narrow
width, including whether I could reach the choices with the keyboard. I reran
the Codd checks for accounts, story gates, advisor previews, endings, ranking,
and save and resume. They all passed.