# Greenpeace Planet 4 Child Theme for GP Netherlands

Child theme for the Planet 4 Wordpress project.
The related master theme’s code lives at:

https://github.com/greenpeace/planet4-master-theme.

Please check the master theme code for more information.


---

# GPNL specific development instructions

# Commit guidelines
Included is a githook *(commit-msg)* which is run to ensure standardized commit messages. [Conventional commits](https://www.conventionalcommits.org/en/v1.0.0/#summary) is used as the convention.
## In short:
```
[optional 'maintenance' type][type]([optional scope])[breaking change]: [description]

[optional body]
```
Section | Explanation
----|----
'Maintenance' type | `Revert` `Merge`
Type | 	`build` `docs` `feat` `fix` `perf` `refactor` `style` `test` `chore`
Scope | Optional explanation of the scope of the change ie `ux` `ui` `admin` etc
Breaking change | When a breaking change is introduced use `!` after the commit type
Description | 10-52 char explanation what the commit does
Body | Optionally more axplanation on the *what* and *why*

The following commits correlate to [SemVer](https://semver.org/#summary) version changes.

Commit | SemVer change
---| ---
commit with `!` | Major version change, ie 2.x.x
commit type `feat` | Minor version change, ie x.2.x
commit with type `fix` | Patch version change, ie x.x.2
