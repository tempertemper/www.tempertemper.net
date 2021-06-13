---
title: Using nvm on macOS
intro: |
  I upgraded node on my laptop and things broke… But I found a way to change the node version, to keep projects alive until there's time to upgrade all those packages and config.
date: 2019-09-23
updated: 2020-10-08
tags:
  - Development
summaryImage: large
---

I recently (accidentally!) upgraded to version 12 of [node](https://nodejs.org/en/) on my Mac; unsurprisingly it broke things. A few of my projects still run Gulp version 3, which isn't compatible with node 12 and above.

Of course, I *should* be upgrading packages to fix vulnerabilities, etc., but in the real world that's not always immediately possible. Thankfully, there are a few ways to downgrade or change the version of node.

The typical way to install node is (was?) with [Homebrew](https://brew.sh/), you can [change the version](https://apple.stackexchange.com/questions/171530/how-do-i-downgrade-node-or-install-a-specific-previous-version-using-homebrew#answer-207883) but it's fiddly and easy to forget you've done it, meaning you'll be using an old version of node for all of your projects.

[nvm](https://github.com/nvm-sh/nvm) (node Version Manager) is a better way, as it allows you to easily switch the version of node you're using for each project that might need a different one.


## Install nvm

1. Run `curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.36.0/install.sh | bash`
2. Close and reopen your terminal, or open a new terminal window
3. Type `nvm` and it'll give you a list of commands and options if you've installed it successfully


## Install the versions of node that you need

You can install as many versions of node that you like.

1. `nvm install node` to install the most recent version
2. `nvm install 10` to install the latest version of node 10 (replace `10` with whatever version you need). You can specify the minor and patch versions too if needed, e.g. `nvm install 10.1.2`
3. Repeat for each different version you need
4. `nvm ls` will list the versions of node you have installed, with an arrow pointing at the version currently in use (normally the last one you installed)


## Set the default node version

You're probably going to want to set the default version of node as the most up to date version.

```bash
nvm alias default node
```

Now when you use `nvm ls` you'll see something like `default -> node (-> v12.19.0)` just after all the node versions you've got installed. That middle '`node`' is what tells you whatever the latest version version is will be the default.


## Switch node version for a project

Now that you've got nvm set up, you can jump between any versions of node that you've installed.

There are three ways to swap node versions:

1. Run a command to specify what version of node to use
2. Run a command to switch to a pre-defined version of node
2. Automatically switch to a pre-defined version of node when you move in/out of a directory

### Run a command with the node version you need

On the face of it, this is the simplest way to do it. Just run a command to use the right version of node for a project, for example `nvm use 12` to use the highest version of node 12 that you have installed.

The problem with this method is that you need to know which version to use before you run the command. You might be lucky and find:

- an .nvmrc file in the project root
- the [`engines` object in the package.json](https://docs.npmjs.com/files/package.json#engines) file

Either of these would tell you which version of node is required to run the project.

### Run a command to switch to a pre-defined version

If a project has an .nvmrc file there's a handy way to switch to the version specified in there without even opening the file; just run:

```bash
npm use
```

When you're finished work, just `cd` out of the project folder and run `npm use` again to switch back to the system default version. Note: if this doesn't work out of the box, add an .nvmrc file to your user root directory by running this:

```bash
echo "default" > ~/.nvmrc
```

The problem with *this* method is that you have to remember to switch back to the global version of node once you're finished work on your project.


### Auto-switch to pre-defined version

So we've got an .nvmrc file in the project and possibly one in your user root directory (`~/`). Wouldn't it be nice if it just changed automatically, rather than your having to run `npm use` every time you jump in/out of a project folder? So it would work something like this:

1. `cd` into your project directory
2. nvm will hook up to the version of node specified in the .nvmrc file
3. When finished work, navigate out of project folder
4. nvm will hook up to the globally installed version of node

That would mean you:

- Never have to check for the node version number in an .nvmrc file
- Won't run into issues because a project needs an older version of node than the one running globally
- Don't have to worry about forgetting to switch back to the global version when you're finished work on that project

Well, all you have to do is add a script to your Bash or Zsh config file (depending on which shell you use -- those two are pretty popular). The script checks for an .nvmrc file; if there is one, it switches to the specified version of node automatically. When you move out of the folder again, it reverts back to the global version.

#### If using Zsh
1. Add [this script](https://github.com/nvm-sh/nvm#zsh) to the bottom of your ~/.zshrc file:
    ```bash
    autoload -U add-zsh-hook
    load-nvmrc() {
      local node_version="$(nvm version)"
      local nvmrc_path="$(nvm_find_nvmrc)"

      if [ -n "$nvmrc_path" ]; then
      local nvmrc_node_version=$(nvm version "$(cat "${nvmrc_path}")")

      if [ "$nvmrc_node_version" = "N/A" ]; then
        nvm install
      elif [ "$nvmrc_node_version" != "$node_version" ]; then
        nvm use
      fi
      elif [ "$node_version" != "$(nvm version default)" ]; then
      echo "Reverting to nvm default version"
      nvm use default
      fi
    }
    add-zsh-hook chpwd load-nvmrc
    load-nvmrc

    ```
2. Run `source ~/.zshrc`

#### If using Bash
1. Add [this script](https://github.com/nvm-sh/nvm#bash) to the bottom of your ~/.bashrc file
    ```bash
    find-up() {
      path=$(pwd)
      while [[ "$path" != "" && ! -e "$path/$1" ]]; do
        path=${path%/*}
      done
      echo "$path"
    }

    cdnvm() {
      cd "$@";
      nvm_path=$(find-up .nvmrc | tr -d '\n')

      if [[ ! $nvm_path = *[^[:space:]]* ]]; then

        declare default_version;
        default_version=$(nvm version default);

        if [[ $default_version == "N/A" ]]; then
          nvm alias default node;
          default_version=$(nvm version default);
        fi

        if [[ $(nvm current) != "$default_version" ]]; then
          nvm use default;
        fi

        elif [[ -s $nvm_path/.nvmrc && -r $nvm_path/.nvmrc ]]; then
        declare nvm_version
        nvm_version=$(<"$nvm_path"/.nvmrc)

        declare locally_resolved_nvm_version
        locally_resolved_nvm_version=$(nvm ls --no-colors "$nvm_version" | tail -1 | tr -d '\->*' | tr -d '[:space:]')

        if [[ "$locally_resolved_nvm_version" == "N/A" ]]; then
          nvm install "$nvm_version";
        elif [[ $(nvm current) != "$locally_resolved_nvm_version" ]]; then
          nvm use "$nvm_version";
        fi
      fi
    }
    alias cd='cdnvm'
    cd $PWD
    ```
2. Run `source ~/.bashrc`


