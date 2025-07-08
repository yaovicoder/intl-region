# 🤝 Contributing to Ydee Intl Region

Thank you for considering contributing! Please follow these guidelines to help us maintain a high-quality project.

---

## Workflow
1. **Fork** the repository and clone your fork.
2. **Create a branch** from `develop` (see [`docs/BRANCHING_STRATEGY.md`](./docs/BRANCHING_STRATEGY.md)).
3. **Name your branch** using the correct prefix and ticket number (e.g., `feature/ITR-0003-region-provider`).
4. **Write code and tests**. Ensure all tests pass locally.
5. **Open a Pull Request** to `develop` (or `main` for hotfixes).
6. **Describe your changes** clearly in the PR template.
7. **Request review** and address feedback.

---

## Code Style
- Follow PSR-12 for PHP code.
- Use meaningful variable and function names.
- Keep functions and classes focused and small.
- Document public methods with PHPDoc.

---

## Commit Messages
- Use present tense: "Add feature" not "Added feature".
- Reference the relevant ticket (e.g., `ITR-0003`).
- Example: `ITR-0003: Add RegionProvider core class`

---

## Pull Requests
- Use the provided PR template in [`docs/templates/PULL_REQUEST_TEMPLATE.md`](./docs/templates/PULL_REQUEST_TEMPLATE.md).
- Link to the relevant user story/ticket.
- Ensure your branch is up to date with `develop` before merging.

---

## Code Review
- Be respectful and constructive.
- Address all comments before merging.
- Approvals from at least one maintainer required.

---

## Tests
- All new features and bugfixes must include tests.
- Run `vendor/bin/phpunit` before pushing.
- See [`docs/TESTING.md`](./docs/TESTING.md) for testing guidelines.

---

Thank you for helping make this project better! 