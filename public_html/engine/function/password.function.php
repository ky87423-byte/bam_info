<?php
/**
 * BAMtube 비밀번호 헬퍼 함수
 *
 * 신규 비밀번호: PASSWORD_BCRYPT 로 저장
 * 기존 MD5 해시: 로그인 성공 시 BCRYPT 로 자동 업그레이드 (회원이 인지하지 못함)
 *
 * 마이그레이션 전략:
 *   - nf_verify_password() 가 BCRYPT / MD5 를 자동 판별
 *   - MD5 검증 성공 시 DB 의 해시를 BCRYPT 로 교체
 *   - 신규 가입 / 비밀번호 변경 / 임시 비밀번호 발급은 모두 nf_hash_password() 사용
 */

/**
 * 비밀번호 해싱 (BCRYPT)
 *
 * @param  string $plain  평문 비밀번호
 * @return string         BCRYPT 해시 문자열 ($2y$...)
 */
function nf_hash_password($plain) {
    return password_hash($plain, PASSWORD_BCRYPT);
}

/**
 * 비밀번호 검증 + MD5→BCRYPT 자동 마이그레이션
 *
 * @param  string        $plain      사용자가 입력한 평문 비밀번호
 * @param  string        $stored     DB 에 저장된 해시 (BCRYPT 또는 MD5)
 * @param  int           $member_no  회원 번호 (업그레이드 저장용, 0 이면 저장 생략)
 * @param  DBConnection  $db         DB 인스턴스
 * @return bool                      비밀번호 일치 여부
 */
function nf_verify_password($plain, $stored, $member_no, $db) {
    if (empty($plain) || empty($stored)) return false;

    // ── 1) BCRYPT 검증 ──────────────────────────────────────────────────────
    if (password_verify($plain, $stored)) {
        // 비용 계수(BCRYPT 라운드)가 변경된 경우 자동 재해싱
        if ($member_no > 0 && password_needs_rehash($stored, PASSWORD_BCRYPT)) {
            $db->_query(
                "update nf_member set `mb_password`=? where `no`=?",
                array(password_hash($plain, PASSWORD_BCRYPT), intval($member_no))
            );
        }
        return true;
    }

    // ── 2) MD5 레거시 해시 검증 (32자 16진수) ────────────────────────────────
    if (strlen($stored) === 32 && ctype_xdigit($stored) && hash_equals($stored, md5($plain))) {
        // MD5 검증 성공 → BCRYPT 로 즉시 업그레이드
        if ($member_no > 0) {
            $db->_query(
                "update nf_member set `mb_password`=? where `no`=?",
                array(password_hash($plain, PASSWORD_BCRYPT), intval($member_no))
            );
        }
        return true;
    }

    return false;
}
