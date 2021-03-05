# tempertemper.net


## Development environment

### Set up
Run `npm install` in the project root to install the node packages.

### Running
Run `npm start` in the project root to spin up the development environment.

### Updating dependencies
Run `npm update` in the project root to update packages.


## Production release

- Create a release branch from `develop`, for example `git checkout -b release/1.0.0`
- Update the `.changelog` file with details of what has been added/changed/fixed/removed
- Bump the version number accordingly:
    - For a major: `npm run bump:major`
    - For a minor: `npm run bump:minor`
    - For a patch: `npm run bump:patch`
- Push the changes to the remote for PR (Pull Request)
- Deal with the merge on the remote; this will trigger a deployment to https://www.tempertemper.net
- Once merged, tag the `main` branch with the new version number
- Pull the updated `main` branch down and delete the release branch
- Merge the updated `main` branch into `develop`, push the updates (the changelog and version number updates) to the remote
