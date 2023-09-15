# tempertemper.net


## Development environment

### Set up

- Clone the remote repo with `git clone git@github.com:tempertemper/www.tempertemper.net.git`
- Ensure you are on the [correct version of node](https://www.tempertemper.net/blog/using-nvm-on-macos)
- Run `npm install` (or `npm i`) in the project root to install the node packages.

### Running
Run `npm start` in the project root to spin up the development environment.

### Updating dependencies
Run `npm update` in the project root to update packages.


## Production release

This repository uses [GitHub Flow](https://www.tempertemper.net/blog/simplifying-branching-and-deployment-with-github-flow).

- Update the `.changelog` file with details of what has been added/changed/fixed/removed
- Bump the version number accordingly:
    - For a major: `npm run bump:major`
    - For a minor: `npm run bump:minor`
    - For a patch: `npm run bump:patch`
- Push the changes to the remote
- Raise a PR (Pull Request) on the remote
- Once all tests have passed, merge the PR; this will trigger a deployment to [www.tempertemper.net](https://www.tempertemper.net) and [automatically tag the merge commit with the version number](https://www.tempertemper.net/blog/version-tagging-with-releases-in-github-flow)
- Run `git switch main && git pull -p && git branch -d name-of-branch` locally
