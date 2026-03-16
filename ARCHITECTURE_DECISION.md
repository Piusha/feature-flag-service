# Architecture Decision

## Context

This take-home backend includes a feature flag module and car damage report APIs.
The assignment scope prioritizes simplicity, deterministic behavior, and fast evaluator setup over advanced targeting.

Originally, evaluation could be modeled with user-targeted rollout (`key:user_id`), but user-level segmentation is not required for this submission.

## Decision

- Evaluate feature flags without user-specific targeting.
- For `rule_based` flags, compute rollout bucket from `feature_flag_key` only.
- Keep evaluation deterministic and global for all consumers.
- Use a global cache key format `flags:v{version}`.
- Invalidate by version bump on feature flag create/update/delete.

## Rationale

- Reduces complexity in domain rules and cache key design.
- Makes behavior easy to reason about during review.
- Matches assignment scope where user-specific rules are out of scope.
- Avoids ambiguity about identity requirements in API requests.

## Consequences

Positive:
- Simpler API behavior and lower cognitive overhead.
- Stable, repeatable evaluation output for the same stored flags.
- Easier to test and debug.

Tradeoffs:
- No per-user rollout targeting.
- Global cache invalidation is broader than selective invalidation.
- Personalization capabilities would require an additional design step.

## Future Extension Path

If user targeting is needed later:

- Extend `EvaluationContext` with user/tenant/device attributes.
- Reintroduce user-aware rollout hashing strategy.
- Update cache key strategy to include normalized targeting dimensions.
- Keep versioned invalidation as a fallback for safety.
