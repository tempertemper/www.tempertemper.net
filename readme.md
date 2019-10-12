# tempertemper.net


## Development environment

### Set up
Run `npm install` in the project root to install the node packages.

### Running
Run `npm start` in the project root to spin up the development environment.

### Updating dependencies
Run `npm update` in the project root to update packages.


## Production release

- Create a release branch
- Bump the version number:
    - For a major  `npm run bump:major`
    - For a minor  `npm run bump:minor`
    - For a patch  `npm run bump:patch`
- Update the .changelog with details of what has been added/changed/fixed/removed
- Tag the branch with the version number
- Merge the release branch into `master` to trigger deploy to https://tempertemper.net
- Merge the release branch into `develop`
