## RULES (NON-NEGOTIABLE)

1. Controllers must NOT contain business logic
2. All writes must go through Actions
3. No model logic in controllers
4. Use DTOs for input data
5. Side effects must use Events/Jobs
6. No direct Mail/Queue calls in controllers