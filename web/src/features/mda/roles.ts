/**
 * The two roles that operate an MDA workspace.
 *
 * One nav serves both: MDA Officer's permission set is a strict subset of MDA Admin's
 * (Admin adds only `beneficiary.approve`, `beneficiary.export`,
 * `beneficiary.access_request`, `user.create`/`user.edit` and `role.view`), so the rail
 * is built once and each item gates on permission rather than branching per role.
 */
export const MDA_ROLES = ['mda_officer', 'mda_admin'] as const
