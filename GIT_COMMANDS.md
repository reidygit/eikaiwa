# Git Commands Reference

## Your Repository Setup

Your Git repository now has **3 commits** with clean history:

1. **Initial commit** - Original Eikaiwa.fm site with Centacast
2. **Radio player implementation** - New self-hosted player
3. **Add .gitignore** - Protect sensitive files

## Useful Git Commands

### Viewing History

```bash
# See commit history
git log

# See compact history
git log --oneline

# See what changed in each commit
git log --stat

# See detailed changes
git show <commit-hash>
```

### Comparing Changes

```bash
# Compare original vs new player
git diff 1c7bde1 2d05b10

# See what files changed
git diff --stat 1c7bde1 2d05b10

# Compare specific file
git diff 1c7bde1 2d05b10 html/home.html
```

### Checking Status

```bash
# See current status
git status

# See what's been staged
git diff --cached

# See unstaged changes
git diff
```

### Making New Changes

```bash
# Stage files for commit
git add .

# Stage specific file
git add path/to/file

# Commit with message
git commit -m "Your commit message"

# Amend last commit (if you forgot something)
git commit --amend
```

### Reverting Changes

```bash
# Revert to original state (before radio player)
git checkout 1c7bde1

# Come back to latest
git checkout master

# Create a new branch from original state
git checkout -b original-state 1c7bde1

# Undo uncommitted changes to a file
git checkout -- path/to/file
```

### Branching

```bash
# Create new branch
git branch feature-name

# Switch to branch
git checkout feature-name

# Create and switch in one command
git checkout -b feature-name

# List branches
git branch

# Merge branch into current
git merge feature-name

# Delete branch
git branch -d feature-name
```

## Pushing to GitHub (When Ready)

### Step 1: Create Repository on GitHub
1. Go to github.com
2. Click "New repository"
3. Name it "eikaiwa" (or whatever you prefer)
4. **Don't** initialize with README (you already have content)

### Step 2: Connect Local to GitHub

```bash
# Add GitHub as remote
git remote add origin https://github.com/YOUR-USERNAME/eikaiwa.git

# Verify remote was added
git remote -v

# Push your code
git push -u origin master
```

### Step 3: Future Pushes

```bash
# After making commits locally
git push
```

## Common Workflows

### Making Changes and Committing

```bash
# 1. Make your changes to files
# 2. Check what changed
git status
git diff

# 3. Stage changes
git add .

# 4. Commit with descriptive message
git commit -m "Add new feature or fix bug"

# 5. Push to GitHub (if set up)
git push
```

### Viewing the Radio Player Changes

```bash
# See all changes in radio player commit
git show 2d05b10

# See specific file changes
git show 2d05b10:html/home.html

# Compare original home.html to new
git diff 1c7bde1:html/home.html 2d05b10:html/home.html
```

### Going Back in Time

```bash
# View file as it was in original commit
git show 1c7bde1:html/home.html

# Temporarily switch to original state
git checkout 1c7bde1
# (Look around, test things)

# Come back to present
git checkout master
```

## Important Notes

- **Never** run `git push --force` unless you know what you're doing
- **Always** check `git status` before committing
- **Write clear** commit messages describing what and why
- **Commit often** - small, focused commits are better than large ones
- **Don't commit** sensitive data (passwords, keys) - that's what .gitignore is for

## Your Commit Hashes

- Original state: `1c7bde1`
- Radio player: `2d05b10`
- .gitignore: `5d6b5b6` (current HEAD)

## Need Help?

- Git documentation: https://git-scm.com/doc
- GitHub guides: https://guides.github.com/
- Interactive tutorial: https://learngitbranching.js.org/
